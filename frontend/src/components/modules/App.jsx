import React, { useEffect, useState } from "react";
import { axiosClient } from "../../api/axios";
import ModuleCard from "../modules/ModuleCard"; // Composant pour afficher un module
import { motion } from "framer-motion";
import { ClipLoader } from "react-spinners";
import { FaSearch } from "react-icons/fa";

function App() {
  const [modules, setModules] = useState([]);
  const [searchQuery, setSearchQuery] = useState(""); // État pour la recherche
  const [filteredModules, setFilteredModules] = useState([]); // Modules filtrés
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchModules = async () => {
      try {
        const response = await axiosClient.get("/api/modules");
        setModules(response.data);
        setFilteredModules(response.data); // Initialiser les modules filtrés avec tous les modules
      } catch (error) {
        console.error("Erreur lors du chargement des modules :", error);
      } finally {
        setLoading(false);
      }
    };

    fetchModules();
  }, []);

  // Filtrer les modules en fonction de la recherche
  const handleSearch = (e) => {
    const query = e.target.value.toLowerCase();
    setSearchQuery(query);
    setFilteredModules(
      modules.filter(
        (module) =>
          module.nomMod.toLowerCase().includes(query) ||
          module.descriptionMod.toLowerCase().includes(query) ||
          module.anneeMod.toLowerCase().includes(query)
      )
    );
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center min-h-screen">
        <ClipLoader size={50} color="#4A90E2" />
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
      <h1 className="text-4xl font-bold text-primary-dark mb-8 text-center">
        Explorez nos Modules
      </h1>

      {/* Barre de recherche */}
      <div className="flex justify-center items-center mb-6">
        <div className="relative w-full max-w-lg">
          <input
            type="text"
            value={searchQuery}
            onChange={handleSearch}
            className="w-full py-3 px-6 rounded-lg border border-gray-300 shadow-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
            placeholder="Recherchez un module par nom, description ou année..."
          />
          <FaSearch className="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-400" />
        </div>
      </div>

      {/* Résultats filtrés */}
      {filteredModules.length > 0 ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredModules.map((module) => (
            <ModuleCard key={module.id} module={module} />
          ))}
        </div>
      ) : (
        <p className="text-center text-gray-500 text-lg">
          Aucun module trouvé pour la recherche : "{searchQuery}"
        </p>
      )}
    </motion.div>
  );
}

export default App;
