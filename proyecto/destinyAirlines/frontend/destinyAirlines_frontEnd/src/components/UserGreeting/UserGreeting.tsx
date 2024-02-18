import styles from "./UserGreeting.module.css";
import { authStore } from "../../store/authStore";

export function UserGreeting() {
    const {
        title,
        firstName,
        lastName,
      } = authStore();
  return (
    <>
      <div className={styles.greeting}>
        <p>{title} {firstName} {lastName}</p>
      </div>
    </>
  );
}
