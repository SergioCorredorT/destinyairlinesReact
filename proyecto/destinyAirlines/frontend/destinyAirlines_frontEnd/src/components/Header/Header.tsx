import styles from './Header.module.css';
import logo from '../../images/Branding/isologo.PNG';
export function Header() {
  return (
    <header>
      <div>
        <img src={logo} />
      </div>
      <div>
        Login
      </div>
    </header>
  );
}
