import React, { useEffect, useState } from "react";
import { axiosClient } from "../../api/axios"; // Import de votre axiosClient
import ModuleCard from "../modules/ModuleCard"; // Composant pour afficher un module
import { motion } from "framer-motion";

function App() {
  const [modules, setModules] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchModules = async () => {
      try {
        const response = await axiosClient.get("/api/modules"); // Utilisation d'axiosClient
        setModules(response.data); // Supposons que l'API renvoie un tableau de modules
      } catch (error) {
        console.error("Erreur lors du chargement des modules :", error);
      } finally {
        setLoading(false);
      }
    };

    fetchModules();
  }, []);

  if (loading) {
    return (
      <div className="flex justify-center items-center min-h-screen">
        <div className="animate-spin h-10 w-10 border-4 border-primary rounded-full"></div>
      </div>
    );
  }

  return (
    <motion.div
      className="container mx-auto px-4 py-8"
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      transition={{ duration: 1 }}
    >
      <h1 className="text-3xl font-bold text-primary mb-6 text-center">
        Liste des Modules
      </h1>
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {modules.map((module) => (
          <ModuleCard key={module.id} module={module} />
        ))}
      </div>
    </motion.div>
  );
}

export default App;
