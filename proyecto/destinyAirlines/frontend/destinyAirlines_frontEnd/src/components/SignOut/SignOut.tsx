import { useAuthStore } from "../../store/authStore";
import styles from "./SignOut.module.css";

export function SignOut() {
  const { reset } = useAuthStore();

  const handleClick = () => {
    reset();
  };

  return (
    <>
      <div className={styles.logoutControls}>
        <button onClick={() => handleClick()}>Sign out</button>
      </div>
    </>
  );
}
