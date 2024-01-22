import styles from "./SignUp.module.css";
export function SignUp() {
  const handleSubmit = () => {
    //TEMPORAL

  };

  return (
    <div className={styles.signUp}>
      <h2>Sign up</h2>
      <form className={styles.form} onSubmit={handleSubmit}>
        <div className={styles.inputGroup}>
          <label htmlFor="emailAddress">Email*</label>
          <input
            type="text"
            id="emailAddress"
            name="emailAddress"
            placeholder="juan@dominio.com"
            title="Introduzca aquí su email"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="password">Password*</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="12345678A"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="firstName">Nombre*</label>
          <input
            type="text"
            id="firstName"
            name="firstName"
            placeholder="Juan"
            title="Introduzca aquí su nombre"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="lastName">Apellidos*</label>
          <input
            type="text"
            id="lastName"
            name="lastName"
            placeholder="Pérez Pérez"
            title="Introduzca aquí su apellido"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="country">País*</label>
          <input
            type="text"
            id="country"
            name="country"
            placeholder="España"
            title="Introduzca aquí su país"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="townCity">Ciudad*</label>
          <input
            type="text"
            id="townCity"
            name="townCity"
            placeholder="Albacete"
            title="Introduzca aquí su ciudad"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="streetAddress">Calle*</label>
          <input
            type="text"
            id="streetAddress"
            name="streetAddress"
            placeholder="Calle 123"
            title="Introduzca aquí su calle"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="zipCode">Zip code*</label>
          <input
            type="number"
            id="zipCode"
            name="zipCode"
            placeholder="12345"
            title="Introduzca aquí su código postal"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="documentType">Tipo de documento*</label>
          <input
            type="text"
            id="documentType"
            name="documentationType"
            placeholder="DNI"
            title="Introduzca aquí su tipo de documento"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="documentCode">Número de documento*</label>
          <input
            type="text"
            id="documentCode"
            name="documentCode"
            placeholder="123456789A"
            title="Introduzca aquí su número de documento"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="expirationDate">Fecha de expiración*</label>
          <input
            type="date"
            id="expirationDate"
            name="expirationDate"
            title="Introduzca aquí su fecha de expiración"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="title">Título</label>
          <input
            type="text"
            id="title"
            name="title"
            title="Introduzca aquí su título"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="phoneNumber1">Número de teléfono 1*</label>
          <input
            type="text"
            id="phoneNumber1"
            name="phoneNumber1"
            placeholder="123456789"
            title="Introduzca aquí su número de teléfono 1"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="phoneNumber2">Número de teléfono 2</label>
          <input
            type="text"
            id="phoneNumber2"
            name="phoneNumber2"
            placeholder="123456789"
            title="Introduzca aquí su número de teléfono 2"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="phoneNumber3">Número de teléfono 3</label>
          <input
            type="text"
            id="phoneNumber3"
            name="phoneNumber3"
            placeholder="123456789"
            title="Introduzca aquí su número de teléfono 3"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="companyName">Nombre de la empresa</label>
          <input
            type="text"
            id="companyName"
            name="companyName"
            placeholder="Juan Company"
            title="Introduzca aquí el nombre de su empresa"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="companyTaxNumber">
            Número de identificación fiscal
          </label>
          <input
            type="text"
            id="companyTaxNumber"
            name="companyTaxNumber"
            placeholder="123456789"
            title="Introduzca aquí el número de identificación fiscal de su empresa"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="companyPhoneNumber">
            Número de teléfono de la empresa
          </label>
          <input
            type="text"
            id="companyPhoneNumber"
            name="companyPhoneNumber"
            placeholder="123456789"
            title="Introduzca aquí el número de teléfono de su empresa"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="dateBirth">Fecha de nacimiento*</label>
          <input
            type="date"
            id="dateBirth"
            name="dateBirth"
            title="Introduzca aquí su fecha de nacimiento"
          />
        </div>
        <div className={styles.inputGroup}>
          <button type="submit">Sign up</button>
        </div>
      </form>
    </div>
  );
}
