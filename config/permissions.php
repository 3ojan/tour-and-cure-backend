<?php

$EClinicOwnerRoles = array(
    "IS_CLINIC_OWNER" => 'is_clinic_owner',
);

$EClinicUserRoles = array(
    "IS_CLINIC" => 'is_clinic',
    "CREATE_EMPLOYEE" => 'create_employee',
    "EDIT_CLINIC" => 'edit_clinic',
    "EDIT_EMPLOYEE_ROLES" => 'edit_employee_roles',
    "SEE_REQUESTS" => 'see_requests',
    "REPLY_TO_REQUESTS" => 'reply_to_requests',
    "SEE_AGENDA" => 'see_agenda',
    "EDIT_AGENDA_ITEM" => 'edit_agenda_item'
);

$EAdminRoles = array(
    "IS_ADMIN" => 'is_admin',
    "CREATE_CLINIC" => 'create_clinic',
    "CREATE_USER" => 'create_user',
    "SEE_MOCKS" => 'see_mocks'
);

$EUserRoles = array(
    "IS_USER" => 'is_user',
    "CREATE_INQUIRY" => 'create_inquiry',
    "CREATE_FAMILY_MEMBER" => 'create_family_member',
    "GET_NOTIFICATION_FOR_FAMILY_MEMBER" => 'get_notification_for_family_member',
);

return [
    'EClinicOwnerRoles' => $EClinicOwnerRoles,
    'EClinicUserRoles' => $EClinicUserRoles,
    'EAdminRoles' => $EAdminRoles,
    'EUserRoles' => $EUserRoles,
];
