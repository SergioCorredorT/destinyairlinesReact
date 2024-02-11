import { ReactNode } from "react";
import styles from "./Modal.module.css";

export function Modal({
  isOpen = true,
  closeModal,
  children,
}: {
  isOpen?: boolean;
  closeModal: () => void;
  children: ReactNode;
}) {
  if (!isOpen) {
    return null;
  }

  const handleClickModal = () => {
    closeModal();
  };

  const handleClickModalPanel = (event: React.MouseEvent<HTMLDivElement>) => {
    event.stopPropagation();
  };

  const handleClickCloseButton = () => {
    closeModal();
  };

  return (
    <div className={styles.modal} onClick={handleClickModal}>
      <div className={styles.modalPanel} onClick={handleClickModalPanel}>
        <div className={styles.closeModal}>
          <span className={styles.closeModal_contain} onClick={handleClickCloseButton}>
            ✕
          </span>
        </div>
        {children}
      </div>
    </div>
  );
}
