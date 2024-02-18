import styles from "./SessionStartControls.module.css";
import { SignIn } from "../SignIn/SignIn";
import { SignUp } from "../SignUp/SignUp";
import { Modal } from "../Modal/Modal";
import { useModal } from "../../hooks/useModal";

export function SessionStartControls() {
  const signInModal = useModal();
  const signUpModal = useModal();

  return (
    <>
      <div className={styles.loginControls}>
        <button onClick={signInModal.openModal}>Iniciar sesión</button>
        <button onClick={signUpModal.openModal}>Registrarse</button>
      </div>
      {
        <Modal isOpen={signInModal.isOpen} closeModal={signInModal.closeModal}>
          <SignIn />
        </Modal>
      }
      {
        <Modal isOpen={signUpModal.isOpen} closeModal={signUpModal.closeModal}>
          <SignUp closeModal={signUpModal.closeModal} />
        </Modal>
      }
    </>
  );
}
