// Header.js
import { useState } from "react";
import logo from "../../images/Branding/isologo.PNG";
import styles from "./Header.module.css";
import { SignIn } from "../SignIn/SignIn";
import { Modal } from "../Modal/Modal";
import { SignUp } from "../SignUp/SignUp";

export function Header() {
  const [isOpenSignIn, setIsOpenSignIn] = useState(false);
  const [isOpenSignUp, setIsOpenSignUp] = useState(false);


  return (
    <header className={styles.header}>
      <div className={styles.logo}>
        <img src={logo} />
      </div>
      <div className={styles.loginControls}>
        <button onClick={() => setIsOpenSignIn(true)}>Sign in</button>
        <button onClick={() => setIsOpenSignUp(true)}>Sign up</button>
      </div>
      <Modal isOpen={isOpenSignIn} onClose={() => setIsOpenSignIn(false)}>
        <SignIn />
      </Modal>
      <Modal isOpen={isOpenSignUp} onClose={() => setIsOpenSignUp(false)}>
        <SignUp />
      </Modal>
    </header>
  );
}
