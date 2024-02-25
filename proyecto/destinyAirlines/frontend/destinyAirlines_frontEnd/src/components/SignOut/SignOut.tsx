import { authStore } from "../../store/authStore";
import styles from "./SignOut.module.css";

export function SignOut() {
  const { signOut } = authStore();

  const handleClick = async () => {
    signOut();
  };

  return (
    <>
      <div className={styles.logoutControls}>
        <button onClick={() => handleClick()}>Cerrar sesión</button>
      </div>
    </>
  );
}
