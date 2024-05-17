<?php

namespace Modules\User\Helpers;

//use File;
//use Image;
use Illuminate\Support\Facades\File;
use Modules\User\Constants\ConversationConstant;

class MessageHelper
{
    protected function parseBody(string $body)
    {
        return explode("|", $body);
    }

    public function getFilePath(int $chat)
    {
        return "chat-files/$chat";
    }

    public function getDownloadPath(string $body)
    {
        return $this->parseBody($body)[0] ?? null;
    }

    public function getOriginalName(string $body)
    {
        return $this->parseBody($body)[1] ?? null;
    }

    public function getFileStoragePath(string $body)
    {
        return storage_path("app/" . $this->getDownloadPath($body));
    }

    public function fileExists(string $body) {
        return File::exists($this->getFileStoragePath($body));
    }

    public function getFileSize(string $body)
    {
        $size = File::size($this->getFileStoragePath($body));

        return humanReadableFilesize($size);
    }

    public function getFileExtension(string $body)
    {
        return File::guessExtension($this->getFileStoragePath($body));
    }

    public function getImagePreviewUrl(string $body)
    {
        return $this->getFileStoragePath($body);
    }

    public function typeByExtension(string $extension)
    {
        if (in_array($extension, ['jpg', 'png'])) {
            return ConversationConstant::MESSAGE_TYPE_PHOTO;
        }

        return ConversationConstant::MESSAGE_TYPE_FILE;
    }

    public function isDownloadable(string $type)
    {
        return in_array($type, ConversationConstant::DOWNLOADABLE_TYPES);
    }

    public function preparePhotoMessage(array $messageData)
    {
        if (!isset($messageData['src'])) {
            $messageData['src'] = $this->getImagePreviewUrl(decrypt($messageData['original_body']));
        }

        return $messageData;
    }

    public function prepareFileMessage(array $messageData)
    {
        $decryptedBody = decrypt($messageData['original_body']);

        $messageData['size'] = $this->getFileSize($decryptedBody);
        $messageData['extension'] = $this->getFileExtension($decryptedBody);

        return $messageData;
    }

    public function prepareMessage(array $messageData)
    {
        $body = $messageData['body'];
        $type = $messageData['type'];

        $messageData['is_downloadable'] = $this->isDownloadable($type);

        $messageData['original_body'] = encrypt($body);
        $messageData['name'] = !$messageData['is_downloadable'] ? $body : $this->getOriginalName($body);
        $messageData['body'] = !$messageData['is_downloadable'] ? $body : $this->getDownloadPath($body);


        $dateTime = \Carbon\Carbon::parse($messageData['created_at']);
        $messageData['date'] = $dateTime->format("F j, Y");
        $messageData['date_group'] = $dateTime->format("Y-m-d");
        $messageData['time'] = $dateTime->format('g:i A');

        return $messageData;
    }

    public function isPhoto(string $type)
    {
        return $type === ConversationConstant::MESSAGE_TYPE_PHOTO;
    }
}
