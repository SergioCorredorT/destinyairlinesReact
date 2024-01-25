import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import styles from "./SignUp.module.css";
import { useEffect, useState } from "react";
import { signUpSchema } from "../../validations/signUpSchema";
import { signUp } from "../../services/signUp";
import { getOptionsForUserRegister } from "../../services/getOptionsForUserRegister";

type Inputs = {
  emailAddress: string;
  password: string;
  confirmPassword: string;
  firstName: string;
  lastName: string;
  country: string;
  townCity: string;
  streetAddress: string;
  zipCode: string;
  documentationType: string;
  documentCode: string;
  expirationDate: string;
  title: string;
  phoneNumber1: string;
  phoneNumber2: string;
  phoneNumber3: string;
  companyName: string;
  companyTaxNumber: string;
  companyPhoneNumber: string;
  dateBirth: string;
};

type optionsForUserRegister = {
  docTypesEs: { [key: string]: string };
  docTypes: { [key: string]: string };
  titles: { [key: string]: string };
  countries: { [key: string]: string };
};

export function SignUp() {
  const [error, setError] = useState<string | null>(null);
  const [optionsForUserRegister, setOptionsForUserRegister] =
    useState<optionsForUserRegister | null>(null);

  useEffect(() => {
    async function helperOptionsForUserRegister() {
      const response = await getOptionsForUserRegister();
      console.log(response);
      if (!response || !response.status) {
        setError("Could not load document types");
        return;
      } else {
        setError(null);
      }
      setOptionsForUserRegister(response.response);
    }
    helperOptionsForUserRegister();
  }, []);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<Inputs>({
    resolver: zodResolver(signUpSchema),
  });

  const onsubmit = handleSubmit((jsonData) => {
    console.log(jsonData);
    signUp(jsonData).then((data) => {
      if (!data.status) {
        setError(data.message);
      } else {
        setError(null);
      }
    });
  });

  return (
    <div className={styles.signUp}>
      <h2>Sign up</h2>
      <form className={styles.form} onSubmit={onsubmit}>
        <div className={styles.inputGroupsContainer}>
          <div className={styles.inputGroup}>
            {errors.emailAddress ? (
              <label htmlFor="emailAddress" className={styles.errorMessage}>
                {errors.emailAddress.message}
              </label>
            ) : (
              <label htmlFor="emailAddress">Email*</label>
            )}
            <input
              type="text"
              id="emailAddress"
              placeholder="juan@dominio.com"
              title="Introduzca aquí su email"
              {...register("emailAddress")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.password ? (
              <label htmlFor="password" className={styles.errorMessage}>
                {errors.password.message}
              </label>
            ) : (
              <label htmlFor="password">Password*</label>
            )}
            <input
              type="password"
              id="password"
              placeholder="12345678A"
              title="Introduzca aquí su contraseña"
              {...register("password")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.confirmPassword ? (
              <label htmlFor="confirmPassword" className={styles.errorMessage}>
                {errors.confirmPassword.message}
              </label>
            ) : (
              <label htmlFor="confirmPassword">Repite password*</label>
            )}
            <input
              type="password"
              id="confirmPassword"
              placeholder="12345678A"
              title="Repita aquí su contraseña"
              {...register("confirmPassword")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.firstName ? (
              <label htmlFor="firstName" className={styles.errorMessage}>
                {errors.firstName.message}
              </label>
            ) : (
              <label htmlFor="firstName">Nombre*</label>
            )}
            <input
              type="text"
              id="firstName"
              placeholder="Juan"
              title="Introduzca aquí su nombre"
              {...register("firstName")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.lastName ? (
              <label htmlFor="lastName" className={styles.errorMessage}>
                {errors.lastName.message}
              </label>
            ) : (
              <label htmlFor="lastName">Apellidos*</label>
            )}
            <input
              type="text"
              id="lastName"
              placeholder="Pérez Pérez"
              title="Introduzca aquí su apellido"
              {...register("lastName")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.country ? (
              <label htmlFor="country" className={styles.errorMessage}>
                {errors.country.message}
              </label>
            ) : (
              <label htmlFor="country">País*</label>
            )}
            <datalist id="countries">
              {optionsForUserRegister &&
                optionsForUserRegister.countries &&
                Object.keys(optionsForUserRegister.countries).map((country) => (
                  <option key={country} value={country}>
                    {country}
                  </option>
                ))}
            </datalist>
            <input
              list="countries"
              id="country"
              title="Introduzca aquí su país"
              {...register("country")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.townCity ? (
              <label htmlFor="townCity" className={styles.errorMessage}>
                {errors.townCity.message}
              </label>
            ) : (
              <label htmlFor="townCity">Ciudad*</label>
            )}
            <input
              type="text"
              id="townCity"
              placeholder="Albacete"
              title="Introduzca aquí su ciudad"
              {...register("townCity")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.streetAddress ? (
              <label htmlFor="streetAddress" className={styles.errorMessage}>
                {errors.streetAddress.message}
              </label>
            ) : (
              <label htmlFor="streetAddress">Calle*</label>
            )}
            <input
              type="text"
              id="streetAddress"
              placeholder="Calle 123"
              title="Introduzca aquí su calle"
              {...register("streetAddress")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.zipCode ? (
              <label htmlFor="zipCode" className={styles.errorMessage}>
                {errors.zipCode.message}
              </label>
            ) : (
              <label htmlFor="zipCode">Código postal*</label>
            )}
            <input
              type="number"
              id="zipCode"
              placeholder="12345"
              title="Introduzca aquí su código postal"
              {...register("zipCode")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.documentationType ? (
              <label
                htmlFor="documentationType"
                className={styles.errorMessage}
              >
                {errors.documentationType.message}
              </label>
            ) : (
              <label htmlFor="documentationType">Tipo de documento*</label>
            )}
            <select
              id="documentationType"
              title="Introduzca aquí su tipo de documento"
              defaultValue="none"
              {...register("documentationType")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {optionsForUserRegister &&
                optionsForUserRegister.docTypesEs &&
                Object.keys(optionsForUserRegister.docTypesEs).map(
                  (docTypeKey) => (
                    <option key={docTypeKey} value={docTypeKey}>
                      {optionsForUserRegister.docTypesEs[docTypeKey]}
                    </option>
                  )
                )}
            </select>
          </div>
          <div className={styles.inputGroup}>
            {errors.documentCode ? (
              <label htmlFor="documentCode" className={styles.errorMessage}>
                {errors.documentCode.message}
              </label>
            ) : (
              <label htmlFor="documentCode">Número de documento*</label>
            )}
            <input
              type="text"
              id="documentCode"
              placeholder="123456789A"
              title="Introduzca aquí su número de documento"
              {...register("documentCode")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.expirationDate ? (
              <label htmlFor="expirationDate" className={styles.errorMessage}>
                {errors.expirationDate.message}
              </label>
            ) : (
              <label htmlFor="expirationDate">Fecha de caducidad*</label>
            )}
            <input
              type="date"
              id="expirationDate"
              title="Introduzca aquí su fecha de expiración"
              {...register("expirationDate")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.title ? (
              <label htmlFor="title" className={styles.errorMessage}>
                {errors.title.message}
              </label>
            ) : (
              <label htmlFor="title">Título</label>
            )}
            <select
              id="title"
              title="Introduzca aquí su título"
              defaultValue="none"
              {...register("title")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {optionsForUserRegister &&
                optionsForUserRegister.titles &&
                Object.keys(optionsForUserRegister.titles).map((title) => (
                  <option key={title} value={title}>
                    {optionsForUserRegister.titles[title]}
                  </option>
                ))}
            </select>
          </div>
          <div className={styles.inputGroup}>
            {errors.phoneNumber1 ? (
              <label htmlFor="phoneNumber1" className={styles.errorMessage}>
                {errors.phoneNumber1.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber1">Número de teléfono 1*</label>
            )}
            <input
              type="text"
              id="phoneNumber1"
              placeholder="123456789"
              title="Introduzca aquí su número de teléfono 1"
              {...register("phoneNumber1")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.phoneNumber2 ? (
              <label htmlFor="phoneNumber2" className={styles.errorMessage}>
                {errors.phoneNumber2.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber2">Número de teléfono 2</label>
            )}
            <input
              type="text"
              id="phoneNumber2"
              placeholder="123456789"
              title="Introduzca aquí su número de teléfono 2"
              {...register("phoneNumber2")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.phoneNumber3 ? (
              <label htmlFor="phoneNumber3" className={styles.errorMessage}>
                {errors.phoneNumber3.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber3">Número de teléfono 3</label>
            )}
            <input
              type="text"
              id="phoneNumber3"
              placeholder="123456789"
              title="Introduzca aquí su número de teléfono 3"
              {...register("phoneNumber3")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.companyName ? (
              <label htmlFor="companyName" className={styles.errorMessage}>
                {errors.companyName.message}
              </label>
            ) : (
              <label htmlFor="companyName">Nombre de empresa</label>
            )}
            <input
              type="text"
              id="companyName"
              placeholder="Juan Company"
              title="Introduzca aquí el nombre de su empresa"
              {...register("companyName")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.companyTaxNumber ? (
              <label htmlFor="companyTaxNumber" className={styles.errorMessage}>
                {errors.companyTaxNumber.message}
              </label>
            ) : (
              <label htmlFor="companyTaxNumber">
                Número de identificación fiscal
              </label>
            )}
            <input
              type="text"
              id="companyTaxNumber"
              placeholder="123456789"
              title="Introduzca aquí el número de identificación fiscal de su empresa"
              {...register("companyTaxNumber")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.companyPhoneNumber ? (
              <label
                htmlFor="companyPhoneNumber"
                className={styles.errorMessage}
              >
                {errors.companyPhoneNumber.message}
              </label>
            ) : (
              <label htmlFor="companyPhoneNumber">
                Número de teléfono de su empresa
              </label>
            )}
            <input
              type="text"
              id="companyPhoneNumber"
              placeholder="123456789"
              title="Introduzca aquí el número de teléfono de su empresa"
              {...register("companyPhoneNumber")}
            />
          </div>
          <div className={styles.inputGroup}>
            {errors.dateBirth ? (
              <label htmlFor="dateBirth" className={styles.errorMessage}>
                {errors.dateBirth.message}
              </label>
            ) : (
              <label htmlFor="dateBirth">Fecha de nacimiento*</label>
            )}
            <input
              type="date"
              id="dateBirth"
              title="Introduzca aquí su fecha de nacimiento"
              {...register("dateBirth")}
            />
          </div>
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit">Registrarse</button>
        </div>
        {error && (
          <div className={styles.errorMessage}>
            <p>{error}</p>
          </div>
        )}
      </form>
    </div>
  );
}
