import logo from "../../images/Branding/isologo.PNG";
import styles from "./Header.module.css";
import { useAuthStore } from "../../store/authStore";
import { UserGreeting } from "../UserGreeting/UserGreeting";
import { SessionStartControls } from "../SessionStartControls/SessionStartControls";
import { SessionEndControls } from "../SessionEndControls/SessionEndControls";

export function Header() {
  const { isLoggedIn } = useAuthStore();
  return (
    <header className={styles.header}>
      <div className={styles.logo}>
        <img src={logo} />
      </div>
      <div className={styles.sessionControlsContainer}>
        {isLoggedIn
        ? (
          <>
            <UserGreeting /> <SessionEndControls />
          </>
        ) : (
          <SessionStartControls />
        )}
      </div>
    </header>
  );
}
