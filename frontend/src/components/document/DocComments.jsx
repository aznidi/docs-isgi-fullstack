import React, { useState } from "react";
import { useForm } from "react-hook-form";
import { axiosClient } from "../../api/axios";
import Swal from "sweetalert2";
import { motion } from "framer-motion";
import { FaUserCircle } from "react-icons/fa";

function DocComments({ comments, documentId }) {
  const { register, handleSubmit, reset } = useForm();
  const [allComments, setAllComments] = useState(comments);

  const onSubmit = async (data) => {
    try {
      const response = await axiosClient.post(`/api/documents/${documentId}/comments`, {
        content: data.comment,
      });
      setAllComments([...allComments, response.data.comment]); // Ajouter le nouveau commentaire
      reset(); // Réinitialiser le formulaire
      Swal.fire("Succès", "Commentaire ajouté avec succès.", "success");
    } catch (error) {
      Swal.fire("Erreur", "Impossible d'ajouter le commentaire.", "error");
    }
  };

  return (
    <motion.div
      className="bg-white p-6 rounded-lg shadow-md space-y-6"
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.8 }}
    >
      <h2 className="text-2xl font-bold text-primary-dark">Commentaires</h2>

      <div className="space-y-6">
        {allComments.length > 0 ? (
            allComments.slice(0, 5).map((comment, index) => (
            <motion.div
                key={index}
                className="bg-gray-100 p-4 rounded-lg shadow-sm flex items-start space-x-4"
                whileHover={{ scale: 1.02 }}
            >
                {/* Image de l'utilisateur */}
                {comment.user?.profile_image ? (
                <img
                    src={
                    comment.user.profile_image.startsWith("http")
                        ? comment.user.profile_image
                        : `http://localhost:8000/storage/${comment.user.profile_image}`
                    }
                    alt={comment.user.name}
                    className="w-12 h-12 rounded-full object-cover"
                />
                ) : (
                <FaUserCircle className="text-gray-400 w-12 h-12" />
                )}

                {/* Contenu du commentaire */}
                <div className="flex-1">
                <p className="font-bold text-gray-800">{comment.user?.name || "Utilisateur inconnu"}</p>
                <p className="text-sm text-gray-500">{new Date(comment.created_at).toLocaleDateString()}</p>
                <p className="text-gray-700 mt-2">{comment.content}</p>
                </div>
            </motion.div>
            ))
        ) : (
            <p className="text-gray-500 italic">Aucun commentaire pour ce document.</p>
        )}
        </div>


      {/* Formulaire pour ajouter un commentaire */}
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <textarea
          {...register("comment", { required: "Veuillez écrire un commentaire." })}
          className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          rows={4}
          placeholder="Ajoutez votre commentaire ici..."
        ></textarea>
        <button
          type="submit"
          className="w-full bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-primary transition"
        >
          Ajouter un commentaire
        </button>
      </form>
    </motion.div>
  );
}

export default DocComments;
