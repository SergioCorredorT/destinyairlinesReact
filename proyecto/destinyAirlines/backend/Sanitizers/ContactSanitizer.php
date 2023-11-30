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
//Si es "", o null, o no está definida no se ejecutará el saneamiento
            if (!empty($data['name'])) $data["name"] = self::sanitizeName($data['name']);
            if (!empty($data['email'])) $data["email"] = self::sanitizeEmail($data['email']);
            if (!empty($data['subject'])) $data["subject"] = self::sanitizeSubject($data['subject']);
            if (!empty($data['message'])) $data["message"] = self::sanitizeMessage($data['message']);
            if (!empty($data['phoneNumber'])) $data["phoneNumber"] = self::sanitizePhoneNumber($data['phoneNumber']);

            return $data;
        }
    }
