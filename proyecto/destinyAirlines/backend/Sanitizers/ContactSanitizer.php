<?php
    class ContactSanitizer
    {
        public static function sanitizeName($name) {
            return htmlspecialchars(trim($name));
        }

        public static function sanitizeEmail($email) {
            return trim($email);
        }

        public static function sanitizeSubject($subject) {
            return htmlspecialchars(trim($subject));
        }

        public static function sanitizePhoneNumber($phoneNumber) {
            return preg_replace('/\D/', '', trim($phoneNumber));
        }

        public static function sanitizeMessage($message) {
            return trim($message);
        }

        public static function sanitize($arrayData)
        {
            $arraySanitized = [];
//Si es "", o null, o no está definida no se ejecutará el saneamiento
            if (!empty($arrayData['name'])) $arraySanitized["name"] = self::sanitizeName($arrayData['name']);
            if (!empty($arrayData['email'])) $arraySanitized["email"] = self::sanitizeEmail($arrayData['email']);
            if (!empty($arrayData['subject'])) $arraySanitized["subject"] = self::sanitizeSubject($arrayData['subject']);
            if (!empty($arrayData['message'])) $arraySanitized["message"] = self::sanitizeMessage($arrayData['message']);
            if (!empty($arrayData['phoneNumber'])) $arraySanitized["phoneNumber"] = self::sanitizePhoneNumber($arrayData['phoneNumber']);

            return $arraySanitized;
        }
    }
