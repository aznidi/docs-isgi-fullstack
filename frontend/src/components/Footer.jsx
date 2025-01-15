import React from "react";
import { FaFacebook, FaTwitter, FaInstagram, FaLinkedin } from "react-icons/fa";
import Button from "../ui/Button";
import { Link } from "react-router-dom";

function Footer() {
  return (
    <footer className="bg-gray-900 text-white py-12">
      <div className="container mx-auto px-6 lg:px-12">
        {/* Section principale */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
          {/* Titre et description */}
          <div>
            <h3 className="text-3xl font-bold text-primary mb-4">ISGIDocs</h3>
            <p className="text-gray-400 text-sm md:text-base leading-relaxed">
              Simplifiez votre apprentissage avec une plateforme intuitive pour accéder à tous vos documents et modules éducatifs.
            </p>
          </div>

          {/* Liens utiles */}
          <div>
            <h4 className="text-xl font-semibold text-primary-light mb-4">Liens utiles</h4>
            <ul className="space-y-3">
              {["Accueil", "Modules", "Documents", "Founders", "Contact-Support"].map((item) => (
                <li key={item}>
                  <Link
                    to={`/${item.toLowerCase()}`}
                    className="text-gray-400 hover:text-primary-light transition-colors"
                    onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}
                  >
                    {item}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Actions rapides */}
          <div>
            <h4 className="text-xl font-semibold text-primary-light mb-4">Actions rapides</h4>
            <Button
              variant="primary"
              size="lg"
              className="w-full mb-4 flex items-center justify-center gap-2 hover:shadow-lg transition-transform duration-300"
            >
              <span>Créer un compte</span>
            </Button>
            <Button
              variant="secondary"
              size="lg"
              className="w-full flex items-center justify-center gap-2 hover:shadow-lg transition-transform duration-300"
            >
              <span>Se connecter</span>
            </Button>
          </div>
        </div>

        {/* Ligne de séparation */}
        <div className="my-8 border-t border-gray-700"></div>

        {/* Section inférieure */}
        <div className="flex flex-col md:flex-row items-center justify-between">
          {/* Icônes sociales */}
          <div className="flex space-x-6 mb-6 md:mb-0">
            <a
              href="https://facebook.com"
              target="_blank"
              rel="noopener noreferrer"
              className="text-gray-400 hover:text-primary-light transition-colors duration-200"
            >
              <FaFacebook className="w-6 h-6" />
            </a>
            <a
              href="https://twitter.com"
              target="_blank"
              rel="noopener noreferrer"
              className="text-gray-400 hover:text-primary-light transition-colors duration-200"
            >
              <FaTwitter className="w-6 h-6" />
            </a>
            <a
              href="https://instagram.com"
              target="_blank"
              rel="noopener noreferrer"
              className="text-gray-400 hover:text-primary-light transition-colors duration-200"
            >
              <FaInstagram className="w-6 h-6" />
            </a>
            <a
              href="https://linkedin.com"
              target="_blank"
              rel="noopener noreferrer"
              className="text-gray-400 hover:text-primary-light transition-colors duration-200"
            >
              <FaLinkedin className="w-6 h-6" />
            </a>
          </div>

          {/* Texte copyright */}
          <p className="text-gray-400 text-center text-sm md:text-base">
            © {new Date().getFullYear()} <span className="text-primary">ISGIDocs</span>. Tous droits réservés.
            <br />
            Développé par <a href="https://aznidi.vercel.app" className="text-primary-dark underline" target="_blank">This Guy</a>
          </p>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
