import { ReactNode } from "react";
import styles from "./Window.module.css";

export function Window({
  isOpen = true,
  closeWindow,
  children,
}: {
  isOpen?: boolean;
  closeWindow: () => void;
  children: ReactNode;
}) {
  if (!isOpen) {
    return null;
  }

  const handleClickCloseButton = () => {
    closeWindow();
  };

  return (
    <div className={styles.windowPanel}>
      <div className={styles.closeWindow}>
        <span
          className={styles.closeWindow_contain}
          onClick={handleClickCloseButton}
        >
          ✕
        </span>
      </div>
      {children}
    </div>
  );
}
