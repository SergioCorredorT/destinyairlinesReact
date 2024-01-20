import { ReactNode } from "react";
import styles from "./Modal.module.css";

export function Modal({ isOpen, children, onClose }: {
  isOpen: boolean;
  children: ReactNode;
  onClose: () => void;
})
{
  if (!isOpen) {
    return null;
  }

  return (
    <div className={styles.modal}>
      <div className={styles.modalPanel}>
        <span className={styles.closeModal} onClick={onClose}>
          ✕
        </span>
        {children}
      </div>
    </div>
  );
}
