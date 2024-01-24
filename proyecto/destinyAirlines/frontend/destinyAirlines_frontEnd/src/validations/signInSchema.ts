import { z } from "zod";

export const signInSchema = z.object({
  emailAddress: z
    .string()
    .email({ message: "Email no válido" })
    .max(200, { message: "Email muy largo" }),
  password: z
    .string()
    .min(8, { message: "Password mínimo 8 caracteres" }),
});
