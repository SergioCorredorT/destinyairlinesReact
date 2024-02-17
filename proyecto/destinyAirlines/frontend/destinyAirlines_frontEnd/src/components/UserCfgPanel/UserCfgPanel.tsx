import styles from "./UserCfgPanel.module.css";
import { RemoveUser } from "../RemoveUser/RemoveUser";
import { UpdateUser } from "../UpdateUser/UpdateUser";
import { useState } from "react";

export function UserCfgPanel() {
  const [isDetailsOpen, setIsDetailsOpen] = useState(false);

  const handleToggle = () => {
    setIsDetailsOpen(!isDetailsOpen);
  };
  return (
    <>
      <div className={styles.UserCfgPanel}>
        <div className={styles.UserCfgPanel_title}>
          <h1>Panel de configuración</h1>
        </div>
        <div className={styles.UserCfgPanel_content}>
          <details name="userConfig" open={isDetailsOpen} onToggle={handleToggle}>
            <summary>Editar usuario</summary>
            <div className="detailsContent">
              <UpdateUser isDetailsOpen={isDetailsOpen} />
            </div>
          </details>
          <details name="userConfig">
            <summary>Borrar mi cuenta</summary>
            <div className="detailsContent">
              <RemoveUser />
            </div>
          </details>
        </div>
      </div>
    </>
  );
}
