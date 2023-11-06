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

        public static function sanitize(array $data)
        {
            $arraySanitized = [];
//Si es "", o null, o no está definida no se ejecutará el saneamiento
            if (!empty($data['name'])) $arraySanitized["name"] = self::sanitizeName($data['name']);
            if (!empty($data['email'])) $arraySanitized["email"] = self::sanitizeEmail($data['email']);
            if (!empty($data['subject'])) $arraySanitized["subject"] = self::sanitizeSubject($data['subject']);
            if (!empty($data['message'])) $arraySanitized["message"] = self::sanitizeMessage($data['message']);
            if (!empty($data['phoneNumber'])) $arraySanitized["phoneNumber"] = self::sanitizePhoneNumber($data['phoneNumber']);

            return $arraySanitized;
        }
    }
