import React, { useState } from "react";
import Swal from "sweetalert2";
import { HashLoader } from "react-spinners";
import Button from "../../ui/Button";
import { axiosClient } from "../../api/axios";

function ProfileImageUploader({ user, fetchUserProfile }) {
  const [selectedFile, setSelectedFile] = useState(null);
  const [previewUrl, setPreviewUrl] = useState(null); // Pour prévisualiser l'image
  const [uploading, setUploading] = useState(false);

  const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      // Vérification du type de fichier (images uniquement)
      if (!file.type.startsWith("image/")) {
        Swal.fire("Erreur", "Veuillez sélectionner un fichier image valide.", "error");
        return;
      }
      setSelectedFile(file);
      setPreviewUrl(URL.createObjectURL(file)); // Générer l'URL pour la prévisualisation
    }
  };

  const handleUpload = async () => {
    if (!selectedFile) {
      Swal.fire("Erreur", "Veuillez sélectionner une image à télécharger.", "error");
      console.log("Aucun fichier sélectionné.");
      return;
    }

    const formData = new FormData();
    formData.append("profile_image", selectedFile);


    try {
      setUploading(true);
      const response = await axiosClient.post("/api/update-avatar", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      if (response.status === 200) {
        Swal.fire("Succès", "Votre image de profil a été mise à jour.", "success");
        fetchUserProfile(); // Actualiser les données utilisateur
      }
    } catch (error) {
      Swal.fire("Erreur", "Impossible de mettre à jour l'image de profil.", "error");
    } finally {
      setUploading(false);
      setSelectedFile(null); // Réinitialiser l'état du fichier
      setPreviewUrl(null); // Réinitialiser la prévisualisation
    }
  };


  return (
    <div className="bg-white shadow-lg rounded-lg p-6 mb-6">
      {/* Titre avec HR */}
      <h3 className="text-xl font-bold mb-4 text-primary-dark">
        Modifier l'image de profil
      </h3>
      <hr className="border-t-2 border-neutral-light mb-6" />

      {/* Disposition principale */}
      <div className="flex flex-col md:flex-row items-center md:items-start gap-6">
        {/* Aperçu de l'image actuelle ou sélectionnée */}
        <div className="flex flex-col items-center">
          <img
            src={
              previewUrl ||
              (user.profile_image?.startsWith("http")
                ? user.profile_image
                : user.profile_image
                ? `http://localhost:8000/storage/${user.profile_image}`
                : "https://via.placeholder.com/150")
            }
            alt="Image de profil"
            className="w-32 h-32 rounded-full shadow-md object-cover"
          />
          <p className="text-sm text-neutral mt-2">Image actuelle ou sélectionnée</p>
        </div>

        {/* Formulaire de téléchargement */}
        <div className="flex flex-col items-center w-full">
          <input
            type="file"
            accept="image/*"
            onChange={handleFileChange}
            className="w-full p-2 border border-neutral-light rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none"
          />
          {selectedFile && (
            <p className="text-sm text-neutral mt-2">
              Fichier sélectionné : <span className="font-medium">{selectedFile.name}</span>
            </p>
          )}
          <div className="flex gap-4 mt-4">
            <button
              onClick={handleUpload}
              className="bg-primary hover:bg-primary-light text-white py-3 px-6 rounded-lg shadow-lg flex items-center gap-2 text-lg transition-all duration-200"
              disabled={uploading || !selectedFile}
            >
              {uploading ? (
                <HashLoader size={20} color="white" />
              ) : (
                <>
                  <span>Mettre à jour l'image</span>
                  <span className="inline-block transition-transform duration-200 group-hover:translate-x-1">
                    →
                  </span>
                </>
              )}
            </button>
            {selectedFile && (
              <button
                onClick={() => {
                  setSelectedFile(null);
                  setPreviewUrl(null);
                }}
                className="bg-neutral hover:bg-neutral-dark text-white py-3 px-6 rounded-lg shadow-lg flex items-center gap-2 text-lg transition-all duration-200"
              >
                <span>Réinitialiser</span>
                <span className="inline-block transition-transform duration-200 group-hover:translate-x-1">
                  ↺
                </span>
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

export default ProfileImageUploader;
