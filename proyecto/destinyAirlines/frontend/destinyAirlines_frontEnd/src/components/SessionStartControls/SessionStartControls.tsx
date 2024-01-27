import { useState } from "react";
import styles from "./SessionStartControls.module.css";
import { SignIn } from "../SignIn/SignIn";
import { SignUp } from "../SignUp/SignUp";
import { Modal } from "../Modal/Modal";

export function SessionStartControls() {
  const [openModal, setOpenModal] = useState("");
  const handleSignInClick = () => {
    setOpenModal("signIn");
  };

  const handleSignUpClick = () => {
    setOpenModal("signUp");
  };

  const handleCloseModal = () => {
    setOpenModal("");
  };
  return (
    <>
      <div className={styles.loginControls}>
        <button onClick={handleSignInClick}>Iniciar sesión</button>
        <button onClick={handleSignUpClick}>Registrarse</button>
      </div>
      {
        <Modal isOpen = {openModal === "signIn"} closeModal={handleCloseModal}>
          <SignIn />
        </Modal>
      }
      {
        <Modal isOpen = {openModal === "signUp"} closeModal={handleCloseModal}>
          <SignUp closeModal={handleCloseModal} />
        </Modal>
      }
    </>
  );
}
