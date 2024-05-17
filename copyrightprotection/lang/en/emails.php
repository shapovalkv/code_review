<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */
    'mail_from_name' => 'Áine',
    'mail_footer_name_from' => 'Áine, Inc',

    //SendNotificationCopyrightAssignedListener
    'project_assign_email' => [
        'notificationSubject' => 'Assignment to Project',
        'notificationGreeting' => 'Hello,',
        'notificationBody' => 'You’ve been assigned a new client: :client to project name: :project_name.',
        'notificationAction' => '',
        'notificationEnd' => '',
    ],

    //SendNotificationCopyrightUnassignedListener
    'project_unassign_email' => [
        'notificationSubject' => 'Unassignment from project',
        'notificationGreeting' => 'Hello,',
        'notificationBody' => 'You’ve been unassigned a client: :client from project name: :project_name.',
        'notificationAction' => '',
        'notificationEnd' => '',
    ],

    //SendNotificationNewCopyrightListener
    'new_project_email' => [
        'notificationSubject' => 'New project created',
        'notificationGreeting' => 'Hello,',
        'notificationBody' => 'User: :user created new project: :project_name',
        'notificationAction' => '',
        'notificationEnd' => 'Thanks.',
    ],

    //SendNotificationNewProjectReportListener
    'new_report_email' => [
        'notificationSubject' => 'New Project Report Created change to: New Report Regarding Your Copyright',
        'notificationGreeting' => 'Hello, :client_first_name',
        'notificationBody' => 'A new report regarding your copyright is available to view on the website. Please reach out to your account manager for any questions.',
        'notificationAction' => '',
        'notificationEnd' => 'Thanks!',
    ],
];
