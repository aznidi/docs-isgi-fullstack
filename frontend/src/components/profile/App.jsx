import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { LOGIN_ROUTE } from "../../router/index";
import { axiosClient } from "../../api/axios";
import { motion } from "framer-motion";
import { ClipLoader } from "react-spinners";
import UserInfo from "./UserInfo";
import ProfileImageUploader from "./ProfileImageUploader";
import FavoriteDocuments from "./FavoriteDocuments";

function App() {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  // Vérifier l'authentification et charger les données utilisateur
  useEffect(() => {
    const token = localStorage.getItem("token");
    if (!token) {
      navigate(LOGIN_ROUTE);
    } else {
      fetchUserProfile();
    }
  }, [navigate]);

  // Fonction pour récupérer les données utilisateur
  const fetchUserProfile = async () => {
    try {
      const response = await axiosClient.get("/api/user");
      setUser(response.data);
    } catch (error) {
      console.error("Erreur lors de la récupération du profil :", error);
    } finally {
      setLoading(false);
    }
  };

  // Afficher un loader pendant le chargement des données
  if (loading) {
    return (
      <div className="flex justify-center items-center min-h-screen">
        <ClipLoader size={50} color="#4A90E2" />
      </div>
    );
  }

  return (
    <motion.div
      initial={{ opacity: 0, y: 50 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="container mx-auto p-6"
    >
      {/* Section de bienvenue */}
      <div className="flex flex-col md:flex-row items-center justify-between bg-white shadow-lg p-6 rounded-lg mb-8">
        {/* Image à gauche */}
        <div className="flex-shrink-0 mb-4 md:mb-0">
          <img
            src={
              user.profile_image?.startsWith("http")
                ? user.profile_image
                : user.profile_image
                ? `http://localhost:8000/storage/${user.profile_image}`
                : "https://via.placeholder.com/150"
            }
            alt="Image de profil"
            className="w-32 h-32 rounded-full shadow-md object-cover"
          />
        </div>

        {/* Texte à droite */}
        <div className="flex flex-col items-start md:items-start md:ml-6">
          <h1 className="text-3xl font-extrabold text-primary-dark mb-2 font-montserrat">Salam {user.name} !</h1>
          <p className="text-neutral text-lg mb-4">
            Bienvenue sur votre tableau de bord. Vous pouvez gérer vos informations, mettre à jour votre photo de profil et explorer vos documents favoris.
          </p>
          <button
            onClick={() => navigate("/favorites")}
            className="bg-primary hover:bg-primary-light text-white py-2 px-4 rounded-lg shadow-lg text-lg transition-all duration-200"
          >
            Explorer vos documents
          </button>
        </div>
      </div>

      {/* Autres sections */}
      <UserInfo user={user} fetchUserProfile={fetchUserProfile} />
      <ProfileImageUploader user={user} fetchUserProfile={fetchUserProfile} />
      <FavoriteDocuments />
    </motion.div>
  );
}

export default App;
