import { ReactNode } from "react";
import styles from "./HamburguerMenu.module.css";

export function HamburguerMenu({ children }: { children: ReactNode }) {
  return (
    <div className={styles.hamburguerComponent}>
      <input type="checkbox" id="toggleMenu" className={styles.toggleMenu}/>
      <label htmlFor="toggleMenu" className={styles.toggleButton}>☰</label>
      <div className={styles.hamburguerMenu}>{children}</div>
    </div>
  );
}
