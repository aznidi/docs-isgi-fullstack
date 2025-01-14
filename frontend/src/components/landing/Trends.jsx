import React, { useEffect, useState } from "react";
import { motion } from "framer-motion";
import { axiosClient } from "../../api/axios"; // Client pour gérer les requêtes API
import Slider from "react-slick"; // Bibliothèque pour le slider
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import { FaArrowRight, FaFileAlt, FaCalendarAlt } from "react-icons/fa";

function Trends() {
  const [modules, setModules] = useState([]);

  // Récupérer les données des modules
  useEffect(() => {
    const fetchModules = async () => {
      try {
        const response = await axiosClient.get("/api/modules");
        setModules(response.data);
      } catch (error) {
        console.error("Erreur lors de la récupération des modules :", error);
      }
    };

    fetchModules();
  }, []);

  // Configuration du slider
  const sliderSettings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  };

  return (
    <div className="container mx-auto py-16 px-6">
      {/* Titre et introduction */}
      <motion.div
        className="text-center mb-8"
        initial={{ opacity: 0, y: -50 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8 }}
      >
        <h2 className="text-4xl font-extrabold text-primary-dark mb-4">
          Modules Tendance
        </h2>
        <p className="text-gray-700 text-lg">
          Découvrez les modules les plus populaires de la semaine. Boostez vos
          connaissances dès maintenant !
        </p>
      </motion.div>

      {/* Slider */}
      <Slider {...sliderSettings}>
        {modules.map((module) => (
          <motion.div
            key={module.id}
            className="p-4"
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.5 }}
          >
            <div className="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow">
              {/* Image du module */}
              <div className="w-full h-40 bg-gray-100 flex items-center justify-center">
                {module.imageMod ? (
                  <img
                    src={module.imageMod}
                    alt={module.nomMod}
                    className="w-full h-full object-contain"
                  />
                ) : (
                  <span className="text-gray-500 text-sm">Aucune image</span>
                )}
              </div>

              {/* Contenu du module */}
              <div className="p-6 text-left">
                <h3 className="text-2xl font-bold text-primary-dark mb-2">
                  {module.nomMod}
                </h3>
                <p className="text-gray-600 text-sm leading-relaxed mb-4">
                  {module.descriptionMod}
                </p>

                {/* Section Année avec icône */}
                <div className="flex items-center text-gray-600 mb-4">
                  <FaCalendarAlt className="text-primary mr-2" />
                  <span className="text-sm font-medium">{module.anneeMod}</span>
                </div>

                {/* Boutons d'action */}
                <div className="flex flex-col md:flex-row gap-3">
                  <motion.button
                    className="bg-primary text-white py-2 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-primary-light"
                    whileHover={{ x: 5 }}
                    onClick={() =>
                      (window.location.href = `/modules/${module.id}`)
                    }
                  >
                    Voir plus
                    <FaArrowRight />
                  </motion.button>

                  <motion.button
                    className="bg-secondary text-white py-2 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-secondary-light"
                    whileHover={{ x: 5 }}
                    onClick={() =>
                      (window.location.href = `/modules/${module.id}/documents`)
                    }
                  >
                    Voir les documents liés
                    <FaFileAlt />
                  </motion.button>
                </div>
              </div>
            </div>
          </motion.div>
        ))}
      </Slider>
    </div>
  );
}

export default Trends;
