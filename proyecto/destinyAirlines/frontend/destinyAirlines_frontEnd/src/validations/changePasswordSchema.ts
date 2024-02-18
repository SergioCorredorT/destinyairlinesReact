import { z } from "zod";

export const changePasswordSchema = z
  .object({
    password: z
      .string()
      .min(8, { message: "Password mínimo 8 caracteres*" })
      .max(100, { message: "Password muy largo*" }),
    confirmPassword: z
      .string()
      .min(8, { message: "Password mínimo 8 caracteres*" })
      .max(100, { message: "Password muy largo*" }),
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: "Las contraseñas no coinciden*",
    path: ["confirmPassword"],
  })
