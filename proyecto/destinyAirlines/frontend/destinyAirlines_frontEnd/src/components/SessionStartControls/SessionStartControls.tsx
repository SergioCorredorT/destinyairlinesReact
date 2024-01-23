import { useState } from "react";
import styles from "./SessionStartControls.module.css";
import { SignIn } from "../SignIn/SignIn";
import { SignUp } from "../SignUp/SignUp";
import { Modal } from "../Modal/Modal";

export function SessionStartControls() {
  const [openModal, setOpenModal] = useState("");

  return (
    <>
      <div className={styles.loginControls}>
        <button onClick={() => setOpenModal("signIn")}>Sign in</button>
        <button onClick={() => setOpenModal("signUp")}>Sign up</button>
      </div>
      {
        <Modal isOpen = {openModal === "signIn"} closeModal={()=>setOpenModal("")}>
          <SignIn />
        </Modal>
      }
      {
        <Modal isOpen = {openModal === "signUp"} closeModal={()=>setOpenModal("")}>
          <SignUp />
        </Modal>
      }
    </>
  );
}
