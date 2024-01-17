import logo from '../../images/Branding/isologo.PNG';
import styles from './Header.module.css';
export function Header() {
  return (
    <header className={styles.header}>
      <div>
        <img src={logo} />
      </div>
      <div className={styles.loginControls}>
        <button>
          Sign in
        </button>
        <button>
          Sign up
        </button>
      </div>
    </header>
  );
}
