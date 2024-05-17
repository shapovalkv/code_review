<?php

namespace App\Http\Chat\Constants;

class ConversationConstant
{
    const DEFAULT_SCREEN = self::SCREEN_MESSAGES;
    const DEFAULT_TAB = self::TAB_PHOTOS;

    const SCREEN_MESSAGES = 'chat-messages-screen';
    const SCREEN_MEDIA = 'chat-media-screen';

    const MESSAGE_TYPE_TEXT = 'text';
    const MESSAGE_TYPE_PHOTO = 'photo';
    const MESSAGE_TYPE_FILE = 'file';

    const TAB_PHOTOS = 'photo';
    const TAB_FILES = 'file';

    const MESSAGE_TYPES = [
        self::MESSAGE_TYPE_TEXT => 'Text',
        self::MESSAGE_TYPE_PHOTO => 'Photo',
        self::MESSAGE_TYPE_FILE => 'File'
    ];

    const DOWNLOADABLE_TYPES = [self::MESSAGE_TYPE_PHOTO, self::MESSAGE_TYPE_FILE];

    const SCREENS = [
        self::SCREEN_MESSAGES => 'Messages',
        self::SCREEN_MEDIA => 'Media'
    ];

    const TABS = [
        self::TAB_PHOTOS => 'Photo',
        self::TAB_FILES => 'Docs'
    ];

    const TAB_COMPONENTS = [
        self::TAB_PHOTOS => 'chat-media-photos-tab',
        self::TAB_FILES => 'chat-media-files-tab'
    ];

    const MESSAGE_FIELDS = ['id', 'conversation_id', 'participation_id', 'body', 'type', 'created_at'];

    const CHAT_VALIDATION_RULES = [
        'message' => 'required_without:files|string|max:3000',
        'files' => 'sometimes|array|max:5',
        'files.*' => 'file|mimes:png,jpg,xls,xlsx,doc,docx,pdf|max:10240',
        'currentFiles' => 'sometimes|array|max:5',
        'currentFiles.*' => 'file|mimes:png,jpg,xls,xlsx,doc,docx,pdf|max:10240'
    ];

    const CHAT_TYPE_GENERAL = 'general';
}
