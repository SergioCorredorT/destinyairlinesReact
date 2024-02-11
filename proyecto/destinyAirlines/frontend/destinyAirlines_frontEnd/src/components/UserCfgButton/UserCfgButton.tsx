import styles from "./UserCfgButton.module.css";
import { UserCfgPanel } from "../UserCfgPanel/UserCfgPanel";
import { Window } from "../Window/Window";
import { useWindow } from "../../hooks/useWindow";

export function UserCfgButton() {
  const window = useWindow();

  return (
    <>
      <div className={styles.UserCfgButton}>
        <button onClick={window.openWindow}>⚙️</button>
      </div>
      {
        <Window isOpen={window.isOpen} closeWindow={window.closeWindow}>
          <UserCfgPanel />
        </Window>
      }
    </>
  );
}
