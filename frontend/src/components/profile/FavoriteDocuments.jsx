import React, { useState, useEffect } from "react";
import { axiosClient } from "../../api/axios";
import { HashLoader } from "react-spinners";
import { FaTrash, FaVideo, FaFilePdf, FaEye, FaStar } from "react-icons/fa";
import Swal from "sweetalert2";
import { motion } from "framer-motion";
import { useNavigate } from "react-router-dom";

function FavoriteDocuments() {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [loadingFav, setLoadingFav] = useState({});
  const navigate = useNavigate();

  useEffect(() => {
    fetchFavoriteDocuments();
  }, []);

  const fetchFavoriteDocuments = async () => {
    setLoading(true);
    try {
      const response = await axiosClient.get("/api/favorites");
      setFavorites(response.data.map((favorite) => ({
        ...favorite,
        isFavorite: true, // Marquer tous les documents favoris au départ
      })));
    } catch (error) {
      console.error("Erreur lors de la récupération des documents favoris :", error);
    } finally {
      setLoading(false);
    }
  };

  const toggleFavorite = async (documentId) => {
    // Démarrer le loader spécifique à ce document
    setLoadingFav((prev) => ({ ...prev, [documentId]: true }));

    try {
      const response = await axiosClient.post(`/api/documents/${documentId}/favorite`);
      setFavorites((prevFavorites) =>
        prevFavorites.map((favorite) =>
          favorite.document.id === documentId
            ? { ...favorite, isFavorite: response.data.status === "added" }
            : favorite
        )
      );

      if (response.data.status === "added") {
        Swal.fire("Succès", "Document ajouté aux favoris", "success");
      } else if (response.data.status === "removed") {
        Swal.fire("Succès", "Document retiré des favoris", "success");
      }
    } catch (error) {
      Swal.fire("Erreur", "Impossible de mettre à jour les favoris", "error");
    } finally {
      // Désactiver le loader spécifique à ce document
      setLoadingFav((prev) => ({ ...prev, [documentId]: false }));
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center min-h-screen">
        <HashLoader size={50} color="#4A90E2" />
      </div>
    );
  }

  return (
    <div className="bg-white shadow-lg rounded-lg p-6 mb-6">
      <motion.h3
        className="text-2xl font-bold mb-4 text-gray-800 text-primary-dark"
        initial={{ opacity: 0, y: -10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
      >
        Vos Documents Favoris
      </motion.h3>
      {favorites.length === 0 ? (
        <motion.p
          className="text-gray-500 text-center"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.5 }}
        >
          Vous n'avez pas encore de documents favoris.
        </motion.p>
      ) : (
        <motion.div
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.8 }}
        >
          {favorites.map((favorite) => (
            <motion.div
              key={favorite.id}
              className="flex flex-col bg-gray-100 p-4 rounded-lg shadow-sm hover:shadow-lg transition-shadow"
            >
              {/* Image du module */}
              <div className="relative w-full h-48 mb-4">
                <img
                  src={`http://localhost:8000/storage/${favorite.document.module.imageMod}`}
                  alt={favorite.document.module.nomMod}
                  className="w-full h-full object-cover rounded-lg"
                />
              </div>

              {/* Informations */}
              <div className="mb-4">
                <h4 className="text-lg font-semibold text-gray-800">{favorite.document.libelleDoc}</h4>
                <p className="text-sm text-gray-600">{favorite.document.module.nomMod}</p>
                <p className="text-sm text-gray-500 mt-1">{favorite.document.descriptionDoc}</p>
              </div>

              {/* Boutons d'action */}
              <div className="flex items-center justify-between mt-auto">
                <button
                  onClick={() => navigate(`/documents/${favorite.document.id}`)}
                  className="flex items-center space-x-1 text-blue-500 hover:text-blue-700"
                >
                  <FaEye />
                  <span>Voir détails</span>
                </button>
                <button
                  onClick={() => toggleFavorite(favorite.document.id)}
                  className={`px-4 py-2 rounded-lg flex items-center justify-center space-x-2 ${
                    favorite.isFavorite ? "bg-yellow-500 text-white" : "bg-gray-300 text-gray-700"
                  }`}
                  disabled={loadingFav[favorite.document.id]}
                >
                  {loadingFav[favorite.document.id] ? (
                    <HashLoader size={20} color="#FFFFFF" />
                  ) : (
                    <>
                      <FaStar />
                      <span>{favorite.isFavorite ? "Retirer des favoris" : "Ajouter aux favoris"}</span>
                    </>
                  )}
                </button>
              </div>
            </motion.div>
          ))}
        </motion.div>
      )}
    </div>
  );
}

export default FavoriteDocuments;
