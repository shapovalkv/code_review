<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckProjectReportFile implements ValidationRule
{
    protected array $desiredSheetNames = ['Google Search', 'Google Images', 'Social Media', 'At Source'];

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @throws Exception
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $path = $value->path();

        if (!file_exists($path)) {
            $fail('Please import correct file');
        }

        $report = IOFactory::load($value->path());

        $sheetTitles = array_map(fn ($sheet) => $sheet->getTitle(), $report->getAllSheets());

        $missingSheets = array_diff($this->desiredSheetNames, $sheetTitles);

        if (!empty($missingSheets)) {
            $this->missingSheets = $missingSheets;
            $missingSheetsStr = implode(', ', $missingSheets);
            $fail("Sheet page named $missingSheetsStr not found in Excel file");
        }

        $sheetCount = $report->getSheetCount();

        $isColumnEmpty = true;

        for ($i = 0; $i < $sheetCount; $i++) {
            $sheet = $report->getSheet($i);
            $isEmpty = true;
            foreach ($sheet->getRowIterator() as $row) {
                $currentRow = $row->getCellIterator()->current();
                if (!is_null($currentRow->getValue())) {
                    $isEmpty = false;
                    break;
                }
            }

            if (!$isEmpty) {
                $isColumnEmpty = false;
                break;
            }
        }

        if ($isColumnEmpty){
            $fail("Please fill the report before import it.");
        }
    }
}
