import { useAuthStore } from "../../store/authStore";
import styles from "./SessionEndControls.module.css";

export function SessionEndControls() {
  const { reset } = useAuthStore();
  const handleClick = () => {
    reset();
  }

  return (
    <>
      <div className={styles.logoutControls}>
        <button onClick={() => handleClick()}>Sign out</button>
      </div>
    </>
  );
}
