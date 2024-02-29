import isologo from "../../images/Branding/isologo.PNG";
import isotipo_small from "../../images/Branding/isotipo_small.PNG";
import styles from "./Header.module.css";
import { authStore } from "../../store/authStore";
import { SessionStartControls } from "../SessionStartControls/SessionStartControls";
import { SessionStartedControls } from "../SessionEndControls/SessionStartedControls";
import { HamburguerMenu } from "../HamburguerMenu/HamburguerMenu";
import { UserGreeting } from "../UserGreeting/UserGreeting";

export function Header() {
  const { isLoggedIn } = authStore();
  return (
    <header className={styles.header}>
      <div className={styles.logo}>
        <img className={styles.isologo} src={isologo} />
        <img className={styles.isotipo_small} src={isotipo_small} />
      </div>
      <div className={styles.sessionControlsContainer}>
       <UserGreeting />
        <HamburguerMenu>
          {isLoggedIn
          ? (
            <>
              <SessionStartedControls />
            </>
          ) : (
            <SessionStartControls />
          )}
        </HamburguerMenu>
      </div>
    </header>
  );
}
