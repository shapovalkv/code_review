<?php

namespace Modules\User\Constants;

class ConversationConstant
{
    public const DEFAULT_SCREEN = self::SCREEN_MESSAGES;
    public const DEFAULT_TAB = self::TAB_PHOTOS;

    public const SCREEN_MESSAGES = 'chat-messages-screen';
    public const SCREEN_MEDIA = 'chat-media-screen';

    public const MESSAGE_TYPE_TEXT = 'text';
    public const MESSAGE_TYPE_PHOTO = 'photo';
    public const MESSAGE_TYPE_FILE = 'file';

    public const TAB_PHOTOS = 'photo';
    public const TAB_FILES = 'file';

    public const MESSAGE_TYPES = [
        self::MESSAGE_TYPE_TEXT => 'Text',
        self::MESSAGE_TYPE_PHOTO => 'Photo',
        self::MESSAGE_TYPE_FILE => 'File'
    ];

    public const DOWNLOADABLE_TYPES = [self::MESSAGE_TYPE_PHOTO, self::MESSAGE_TYPE_FILE];

    public const SCREENS = [
        self::SCREEN_MESSAGES => 'Messages',
        self::SCREEN_MEDIA => 'Media'
    ];

    public const TABS = [
        self::TAB_PHOTOS => 'Photo',
        self::TAB_FILES => 'Docs'
    ];

    public const TAB_COMPONENTS = [
        self::TAB_PHOTOS => 'chat-media-photos-tab',
        self::TAB_FILES => 'chat-media-files-tab'
    ];

    public const MESSAGE_FIELDS = ['id', 'conversation_id', 'participation_id', 'body', 'type', 'created_at'];

    public const CHAT_VALIDATION_RULES = [
//        'message' => 'required_without:files|string|max:3000',
        'message' => 'required|string|max:3000',
//        'files' => 'sometimes|array|max:5',
//        'files.*' => 'file|mimes:png,jpg,xls,xlsx,doc,docx,pdf|max:10240',
//        'currentFiles' => 'sometimes|array|max:5',
//        'currentFiles.*' => 'file|mimes:png,jpg,xls,xlsx,doc,docx,pdf|max:10240'
    ];

    public const CHAT_TYPE_GENERAL = 'general';
}
