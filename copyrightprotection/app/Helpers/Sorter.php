<?php

namespace App\Helpers;

class Sorter
{
    private $orderByColumns = [];
    private $htmlAscSuffix = '';
    private $htmlDescSuffix = '';
    private $cssAscClass = '';
    private $cssDescClass = '';

    private $selectedHeaderClass = '';
    private $notSelectedHeaderClass = '';
    private $orderByDirection = '';
    private $orderByColumn = '';

    /**
     * @param $orderByColumns - keys are used in param string, values are used in sql (will substitute into "orderBy")
     */
    public function __construct(array $orderByColumns)
    {
        $this->orderByColumns = $orderByColumns;
        $params = request()->all();
        $this->orderByColumn = $params['orderByColumn'] ?? array_key_first($this->orderByColumns);
        $this->orderByDirection = $params['orderByDirection'] ?? 'asc';
    }

    public function getOrderByColumn()
    {
        return $this->orderByColumns[$this->orderByColumn];
    }

    public function getOrderByDirection()
    {
        return $this->orderByDirection;
    }

    public function setAscSuffix(string $html)
    {
        $this->htmlAscSuffix = $html;
    }

    public function setDescSuffix(string $html)
    {
        $this->htmlDescSuffix = $html;
    }
    public function setAscClass(string $class)
    {
        $this->cssAscClass = $class;
    }

    public function setDescClass(string $class)
    {
        $this->cssDescClass = $class;
    }

    public function setClassForSelectedHeader(string $str)
    {
        $this->selectedHeaderClass = $str;
    }

    public function setClassForNotSelectedHeader(string $str)
    {
        $this->notSelectedHeaderClass = $str;
    }

    public function sortableLink(string $columnName, string $columnHeader)
    {
        $params = request()->all();
        if (!isset($params['orderByDirection'])) {
            $params['orderByDirection'] = 'asc';
        }

        if (isset($params['orderByColumn']) && $params['orderByColumn'] === $columnName) {
            $params['orderByDirection'] = strtolower($params['orderByDirection']) === 'asc' ? 'desc' : 'asc';
            $linkCaption = $params['orderByDirection'] === 'asc' ? $columnHeader.$this->htmlDescSuffix : $columnHeader.$this->htmlAscSuffix;
            $class = $params['orderByDirection'] === 'asc' ? $this->selectedHeaderClass.' '.$this->cssDescClass : $this->selectedHeaderClass.' '.$this->cssAscClass;
        } else {
            $params['orderByColumn'] = $columnName;
            $params['orderByDirection'] = 'asc';
            $linkCaption = $columnHeader;
            $class = $this->notSelectedHeaderClass;
        }

        $href = request()->url().'?'.http_build_query($params);

        return '<a href="'.$href.'" class="'.$class.'">'.$linkCaption.'</a>';
    }

}
