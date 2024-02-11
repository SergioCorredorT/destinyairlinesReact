import { useState } from 'react';

export function useWindow() {
    const [isOpen, setIsOpen] = useState(false);

    function openWindow() {
        setIsOpen(true);
    }

    function closeWindow() {
        setIsOpen(false);
    }

    return {
        isOpen,
        openWindow,
        closeWindow
    }
}
