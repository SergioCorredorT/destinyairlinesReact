import { SignOut } from "../SignOut/SignOut";
import { UserCfgButton } from "../UserCfgButton/UserCfgButton";
import { UserGreeting } from "../UserGreeting/UserGreeting";
import styles from "./SessionStartedControls.module.css";

export function SessionStartedControls() {
  return (
    <>
      <SignOut />
      <UserCfgButton />
    </>
  );
}
