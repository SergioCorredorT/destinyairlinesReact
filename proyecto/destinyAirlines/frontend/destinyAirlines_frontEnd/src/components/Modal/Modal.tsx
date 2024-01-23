import { ReactNode } from "react";
import styles from "./Modal.module.css";

export function Modal({
  isOpen = true,
  closeModal,
  children,
}: {
  isOpen: boolean;
  closeModal: () => void;
  children: ReactNode;
}) {

  if (!isOpen) {
    return null;
  }

  const handleClick = () => {
    closeModal();
  };

  return (
    <div className={styles.modal}>
      <div className={styles.modalPanel}>
        <span className={styles.closeModal} onClick={handleClick}>
          ✕
        </span>
        {children}
      </div>
    </div>
  );
}
