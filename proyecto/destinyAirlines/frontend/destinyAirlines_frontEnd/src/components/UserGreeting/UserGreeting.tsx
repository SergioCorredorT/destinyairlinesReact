import styles from "./UserGreeting.module.css";
import { useAuthStore } from "../../store/authStore";

export function UserGreeting() {
    const {
        title,
        firstName,
        lastName,
      } = useAuthStore();
  return (
    <>
      <div className={styles.greeting}>
        <p>{title} {firstName} {lastName}</p>
      </div>
    </>
  );
}
