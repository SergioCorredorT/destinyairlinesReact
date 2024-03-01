<?php
require_once ROOT_PATH . '/DataProcessing/Filters/BaseFilter.php';
final class UserFilter  extends BaseFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filterGetUserEditableInfoData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'accessToken' => '',
            'emailAddress' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterCreateUserData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'documentationType' => '',
            'documentCode' => '',
            'expirationDate' => '',
            'title' => null,
            'firstName' => '',
            'lastName' => '',
            'townCity' => '',
            'streetAddress' => '',
            'zipCode' => '',
            'country' => '',
            'emailAddress' => '',
            'password' => '',
            'phoneNumber1' => '',
            'phoneNumber2' => null,
            'phoneNumber3' => null,
            'companyName' => null,
            'companyTaxNumber' => null,
            'companyPhoneNumber' => null,
            'dateBirth' => '',
            'captchaToken' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterUpdateUserData(array $POST): array
    {
        $userData = null;
        $optional_keys_default = [
            'title' => null,
            'firstName' => '',
            'lastName' => '',
            'townCity' => '',
            'streetAddress' => '',
            'zipCode' => '',
            'country' => '',
            'phoneNumber1' => '',
            'phoneNumber2' => null,
            'phoneNumber3' => null,
            'companyName' => null,
            'companyTaxNumber' => null,
            'companyPhoneNumber' => null,
            'documentationType' => '',
            'documentCode' => '',
            'expirationDate' => '',
            'dateBirth' => ''
        ];

        $fixed_keys_default = [
            'accessToken' => '',
            'emailAddressAuth' => ''
        ];

        //Recogemos los campos opcionales, con valor opcional (null), o con valor a validar ('')
        foreach ($optional_keys_default as $key => $defaultValue) {
            if (isset($POST[$key])) {
                $userData[$key] = !empty($POST[$key]) ? $POST[$key] : $defaultValue; //Antes que poner un '' ponemos un null
            }
        }
        foreach ($fixed_keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterUpdatePasswordData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'oldPassword' => '',
            'password' => '',
            'accessToken' => '',
            'emailAddress' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterDeleteUserData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'emailAddress' => '',
            'password' => '',
            'refreshToken' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterLoginUserData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'emailAddress' => '',
            'password' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterGoogleLoginUserData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'googleToken' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }

    public function filterPasswordResetData(array $POST): array
    {
        $userData = null;
        $keys_default = [
            'type' => '',
            'new_password' => '',
            'confirm_password' => '',
            'passwordResetToken' => '',
            'tempId' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $userData;
    }
}
