import React from "react";
import { motion } from "framer-motion";

function ModuleCard({ module }) {
  return (
    <motion.div
      className="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition-all duration-300"
      whileHover={{ scale: 1.05 }}
      whileTap={{ scale: 0.95 }}
    >
      <h2 className="text-lg font-bold text-primary">{module.nomMod}</h2>
      <p className="text-neutral mt-2">{module.descriptionMod}</p>
      <div className="mt-4">
        <button className="bg-primary hover:bg-primary-light text-white py-2 px-4 rounded-lg">
          Voir les documents
        </button>
      </div>
    </motion.div>
  );
}

export default ModuleCard;
