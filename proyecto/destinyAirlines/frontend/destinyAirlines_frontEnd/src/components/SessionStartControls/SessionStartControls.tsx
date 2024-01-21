import { useState } from "react";
import styles from "./SessionStartControls.module.css";
import { SignIn } from "../SignIn/SignIn";
import { SignUp } from "../SignUp/SignUp";
import { Modal } from "../Modal/Modal";

export function SessionStartControls() {
  const [isOpenSignIn, setIsOpenSignIn] = useState(false);
  const [isOpenSignUp, setIsOpenSignUp] = useState(false);

  return (
    <>
      <div className={styles.loginControls}>
        <button onClick={() => setIsOpenSignIn(true)}>Sign in</button>
        <button onClick={() => setIsOpenSignUp(true)}>Sign up</button>
      </div>
      <Modal isOpen={isOpenSignIn} onClose={() => setIsOpenSignIn(false)}>
        <SignIn isOpen= {setIsOpenSignIn}/>
      </Modal>
      <Modal isOpen={isOpenSignUp} onClose={() => setIsOpenSignUp(false)}>
        <SignUp />
      </Modal>
    </>
  );
}
