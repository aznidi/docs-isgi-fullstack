import React from "react";
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as Yup from "yup";
import Swal from "sweetalert2";
import Button from "../../ui/Button";
import { HashLoader } from "react-spinners";
import { axiosClient } from "../../api/axios";

function UserInfo({ user, fetchUserProfile }) {
  return (
    <div className="bg-white shadow-lg rounded-lg p-6 mb-6">
      <h3 className="text-xl font-bold mb-4 text-primary-dark">Modifier vos informations</h3>
      <hr className="border-t-2 border-neutral-light mb-6" />
      <Formik
        initialValues={{
          username: user?.name || "",
          email: user?.email || "",
        }}
        enableReinitialize
        validationSchema={Yup.object({
          username: Yup.string()
            .min(3, "Le nom d'utilisateur doit contenir au moins 3 caractères")
            .required("Nom d'utilisateur requis"),
          email: Yup.string()
            .email("Email invalide")
            .required("Email requis"),
        })}
        onSubmit={async (values, { setSubmitting }) => {
          try {
            let usernameUpdated = false;
            let emailUpdated = false;

            // Mise à jour du nom d'utilisateur uniquement si modifié
            if (values.username !== user?.name) {
              const usernameResponse = await axiosClient.put("/api/update-username", {
                username: values.username,
              });
              if (usernameResponse.status === 200) usernameUpdated = true;
            }

            // Mise à jour de l'email uniquement si modifié
            if (values.email !== user?.email) {
              const emailResponse = await axiosClient.put("/api/update-email", {
                email: values.email,
              });
              if (emailResponse.status === 200) emailUpdated = true;
            }

            // Vérifier si au moins un champ a été mis à jour
            if (usernameUpdated || emailUpdated) {
              Swal.fire("Succès", "Vos informations ont été mises à jour.", "success");
              fetchUserProfile(); // Actualiser les données utilisateur
            } else {
              Swal.fire("Info", "Aucune information n'a été modifiée.", "info");
            }
          } catch (error) {
            Swal.fire(
              "Erreur",
              error.response?.data?.message || "Impossible de mettre à jour les informations.",
              "error"
            );
          } finally {
            setSubmitting(false);
          }
        }}
      >
        {({ isSubmitting }) => (
          <Form>
            {/* Nom d'utilisateur */}
            <div className="mb-4">
              <label htmlFor="username" className="block text-sm font-medium text-neutral-dark">
                Nouveau nom d'utilisateur
              </label>
              <Field
                type="text"
                name="username"
                className="w-full mt-1 p-3 border border-neutral-light rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none"
              />
              <ErrorMessage
                name="username"
                component="div"
                className="text-danger text-sm mt-1"
              />
            </div>

            {/* Email */}
            <div className="mb-4">
              <label htmlFor="email" className="block text-sm font-medium text-neutral-dark">
                Nouvel email
              </label>
              <Field
                type="email"
                name="email"
                className="w-full mt-1 p-3 border border-neutral-light rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none"
              />
              <ErrorMessage
                name="email"
                component="div"
                className="text-danger text-sm mt-1"
              />
            </div>

            {/* Bouton de soumission */}
            <Button
              type="submit"
              variant="primary"
              size="md"
              className="w-full"
              disabled={isSubmitting}
            >
              {isSubmitting ? <HashLoader size={20} color="white" /> : "Mettre à jour"}
            </Button>
          </Form>
        )}
      </Formik>
    </div>
  );
}

export default UserInfo;
