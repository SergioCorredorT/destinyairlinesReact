import { ReactNode } from "react";
import styles from "./Modal.module.css";

export function Modal({
  isOpen = true,
  closeButton = true,
  closeModal,
  children,
}: {
  isOpen?: boolean;
  closeButton?: boolean;
  closeModal?: () => void;
  children: ReactNode;
}) {
  if (!isOpen) {
    return null;
  }

  const handleClickModal = () => {
    if (closeModal) {
      closeModal();
    }
  };

  const handleClickModalPanel = (event: React.MouseEvent<HTMLDivElement>) => {
    event.stopPropagation();
  };

  const handleClickCloseButton = () => {
    if (closeModal) {
      closeModal();
    }
  };

  return (
    <div className={styles.modal} onClick={handleClickModal}>
      <div className={styles.modalPanel} onClick={handleClickModalPanel}>
        <div className={styles.closeModal}>
          {closeButton && (
            <span
              className={styles.closeModal_contain}
              onClick={handleClickCloseButton}
            >
              ✕
            </span>
          )}
        </div>
        {children}
      </div>
    </div>
  );
}
