import React, { useState, useEffect } from "react";
import { axiosClient } from "../../api/axios";
import { ClipLoader } from "react-spinners";
import { FaTrash, FaFilePdf } from "react-icons/fa";
import Swal from "sweetalert2";

function FavoriteDocuments() {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchFavoriteDocuments();
  }, []);

  const fetchFavoriteDocuments = async () => {
    try {
      const response = await axiosClient.get("/api/favorite-documents");
      setFavorites(response.data);
    } catch (error) {
      console.error("Erreur lors de la récupération des documents favoris :", error);
    } finally {
      setLoading(false);
    }
  };

  const handleRemoveFavorite = async (documentId) => {
    try {
      const response = await axiosClient.delete(`/api/favorite-documents/${documentId}`);
      if (response.status === 200) {
        Swal.fire("Succès", "Document supprimé des favoris.", "success");
        fetchFavoriteDocuments(); // Actualiser la liste
      }
    } catch (error) {
      Swal.fire("Erreur", "Impossible de supprimer le document.", "error");
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center min-h-screen">
        <ClipLoader size={50} color="#4A90E2" />
      </div>
    );
  }

  return (
    <div className="bg-white shadow-lg rounded-lg p-6 mb-6">
      <h3 className="text-xl font-bold mb-4">Vos Documents Favoris</h3>
      {favorites.length === 0 ? (
        <p className="text-gray-500">Vous n'avez pas encore de documents favoris.</p>
      ) : (
        <ul className="space-y-4">
          {favorites.map((doc) => (
            <li
              key={doc.id}
              className="flex items-center justify-between p-4 bg-gray-100 rounded-lg"
            >
              <div className="flex items-center space-x-4">
                <FaFilePdf className="text-red-500 text-xl" />
                <div>
                  <p className="font-bold text-gray-800">{doc.title}</p>
                  <p className="text-sm text-gray-600">{doc.module}</p>
                </div>
              </div>
              <button
                onClick={() => handleRemoveFavorite(doc.id)}
                className="text-red-500 hover:text-red-700"
              >
                <FaTrash />
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}

export default FavoriteDocuments;
