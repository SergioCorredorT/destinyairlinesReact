import styles from "./UserCfgPanel.module.css";

export function UserCfgPanel() {

  const handleSubmitUpdateUser = (() => {
    
  });
  
  const handleSubmitDeleteAccount = ((event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    //Crear/Llamar al service
    //desloguear si no hubo error, mostrar error si lo hubo
  });

  return (
    <>
      <div className={styles.UserCfgPanel}>
        <div className={styles.UserCfgPanel_title}>
          <h1>Panel de configuración</h1>
        </div>
        <div className={styles.UserCfgPanel_content}>
          <details name="userConfig">
            <summary>Editar usuario</summary>
            <div className="detailsContent">
              <form onSubmit={handleSubmitUpdateUser}>
                <div className={styles.inputContainer}>
                  <label htmlFor="firstName">Nombre</label>
                  <input
                    type="text"
                    name="firstName"
                    id="firstName"
                    placeholder="Nombre"
                  />
                </div>
                <div className={styles.inputContainer}>
                  <label htmlFor="lastName">Apellido</label>
                  <input
                    type="text"
                    name="lastName"
                    id="lastName"
                    placeholder="Apellido"
                  />
                </div>
                <div className={styles.inputContainer}>
                  <label htmlFor="email">Email</label>
                  <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Email"
                  />
                </div>
                <div className={styles.buttonsContainer}>
                  <button type="submit">Guardar cambios</button>
                </div>
              </form>
            </div>
          </details>
          <details name="userConfig">
            <summary>Borrar mi cuenta</summary>
            <div className="detailsContent">
              <form onSubmit={handleSubmitDeleteAccount}>
                <div className={styles.buttonsContainer}>
                  <button type="submit">Borrar mi cuenta</button>
                </div>
              </form>
            </div>
          </details>
        </div>
      </div>
    </>
  );
}
