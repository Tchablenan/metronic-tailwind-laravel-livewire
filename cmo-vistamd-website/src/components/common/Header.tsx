// src/components/Header.tsx
import { useState, useEffect } from 'react'
import { Menu, X, Phone } from 'lucide-react'
import logoImage from '@/assets/logo-cmo-vistamd.jpg'

const Header = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false)
  const [scrollPosition, setScrollPosition] = useState(0)
  const [activeSection, setActiveSection] = useState('home')

  const navItems = [
    { label: 'Accueil', id: 'home' },
    { label: 'Nous', id: 'about' },
    { label: 'Services', id: 'services' },
    { label: 'Équipe', id: 'team' },
    { label: 'Blog', id: 'blog' },
    { label: 'FAQ', id: 'faq' },
    { label: 'Partenaires', id: 'partners' },
    { label: 'Témoignages', id: 'testimonials' },
    { label: 'Contact', id: 'contact' }
  ]

  // Scroll effect avec détection améliorée
  useEffect(() => {
    const handleScroll = () => {
      const position = window.scrollY
      setScrollPosition(position)

      // Détecte la section active - MODIFIÉ pour chercher tous les éléments avec id
      const sections = navItems.map(item => document.getElementById(item.id)).filter(Boolean)
      
      let currentSection = 'home'
      
      sections.forEach((section) => {
        if (section) {
          const sectionTop = section.offsetTop - 150 // Offset pour le header
          const sectionHeight = section.offsetHeight
          const sectionBottom = sectionTop + sectionHeight
          
          if (position >= sectionTop && position < sectionBottom) {
            currentSection = section.id
          }
        }
      })
      
      setActiveSection(currentSection)
    }

    handleScroll() // Appel initial
    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  const scrollToSection = (sectionId: string) => {
    setIsMenuOpen(false)
    const element = document.getElementById(sectionId)
    if (element) {
      const headerOffset = 80
      const elementPosition = element.getBoundingClientRect().top
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      })
    }
  }

  const isSticky = scrollPosition > 50
  const headerOpacity = Math.min(scrollPosition / 100, 1)

  return (
    <header
      style={{
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        zIndex: 1000,
        transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        background: isSticky
          ? `rgba(15, 23, 42, ${0.95 + headerOpacity * 0.05})`
          : 'rgba(15, 23, 42, 0)',
        backdropFilter: isSticky ? 'blur(10px)' : 'blur(0px)',
        borderBottom: isSticky
          ? '1px solid rgba(34, 197, 94, 0.1)'
          : '1px solid transparent',
        boxShadow: isSticky ? '0 10px 30px rgba(0, 0, 0, 0.2)' : 'none',
        padding: isSticky ? '0.75rem 0' : '1.5rem 0'
      }}
    >
      <div style={{
        maxWidth: '1400px',
        margin: '0 auto',
        padding: '0 2rem',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center'
      }}>
        {/* Logo */}
        <div
          onClick={() => scrollToSection('home')}
          style={{
            cursor: 'pointer',
            display: 'flex',
            alignItems: 'center',
            gap: '0.75rem',
            height: '60px',
            transition: 'all 0.3s ease'
          }}
          onMouseEnter={(e) => {
            e.currentTarget.style.transform = 'scale(1.05)'
          }}
          onMouseLeave={(e) => {
            e.currentTarget.style.transform = 'scale(1)'
          }}
        >
          <img 
            src={logoImage}
            alt="CMO VISTAMD Logo"
            style={{
              height: '100%',
              width: 'auto',
              maxWidth: '200px',
              objectFit: 'contain',
              objectPosition: 'left center'
            }}
          />
        </div>

        {/* Desktop Navigation */}
        <nav
          style={{
            display: 'flex',
            gap: '2rem',
            alignItems: 'center'
          }}
        >
          {navItems.map((item) => (
            <button
              key={item.id}
              onClick={() => scrollToSection(item.id)}
              style={{
                background: 'none',
                border: 'none',
                color: activeSection === item.id ? '#22c55e' : '#cbd5e1',
                cursor: 'pointer',
                fontSize: '1.5rem',
                fontWeight: activeSection === item.id ? '600' : '500',
                transition: 'all 0.3s ease',
                position: 'relative',
                padding: '0.5rem 0'
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.color = '#22c55e'
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.color =
                  activeSection === item.id ? '#22c55e' : '#cbd5e1'
              }}
            >
              {item.label}
              {activeSection === item.id && (
                <div
                  style={{
                    position: 'absolute',
                    bottom: '-5px',
                    left: 0,
                    right: 0,
                    height: '2px',
                    background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                    animation: 'slideIn 0.3s ease'
                  }}
                />
              )}
            </button>
          ))}
        </nav>

        {/* CTA Buttons */}
        <div
          style={{
            display: 'flex',
            gap: '1rem',
            alignItems: 'center'
          }}
        >
          <a
            href="+2250700000000"
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem',
              padding: '0.7rem 1.25rem',
              background: 'transparent',
              border: '1px solid rgba(34, 197, 94, 0.5)',
              color: '#22c55e',
              borderRadius: '0.5rem',
              textDecoration: 'none',
              fontSize: '0.9rem',
              fontWeight: '600',
              transition: 'all 0.3s ease',
              cursor: 'pointer'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.background = 'rgba(34, 197, 94, 0.1)'
              e.currentTarget.style.borderColor = '#22c55e'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.background = 'transparent'
              e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.5)'
            }}
          >
            <Phone size={16} />
            <span>Urgence</span>
          </a>

          <button
            onClick={() => scrollToSection('contact')}
            style={{
              padding: '0.8rem 1.5rem',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              color: 'white',
              border: 'none',
              borderRadius: '0.5rem',
              fontWeight: '600',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              fontSize: '0.95rem'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'translateY(-2px)'
              e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.3)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'translateY(0)'
              e.currentTarget.style.boxShadow = 'none'
            }}
          >
            Rendez-vous
          </button>

          {/* Mobile Menu Button */}
          <button
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            style={{
              background: 'none',
              border: 'none',
              color: '#22c55e',
              cursor: 'pointer',
              display: 'none'
            }}
            className="mobile-menu-btn"
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>
      </div>

      {/* Mobile Navigation */}
      {isMenuOpen && (
        <div
          style={{
            background: 'rgba(15, 23, 42, 0.95)',
            backdropFilter: 'blur(10px)',
            borderTop: '1px solid rgba(34, 197, 94, 0.1)',
            paddingTop: '1rem',
            display: 'none'
          }}
          className="mobile-nav"
        >
          <div style={{
            maxWidth: '1400px',
            margin: '0 auto',
            padding: '0 2rem',
            display: 'flex',
            flexDirection: 'column',
            gap: '1rem'
          }}>
            {navItems.map((item) => (
              <button
                key={item.id}
                onClick={() => scrollToSection(item.id)}
                style={{
                  background: activeSection === item.id
                    ? 'rgba(34, 197, 94, 0.1)'
                    : 'transparent',
                  border: 'none',
                  color: activeSection === item.id ? '#22c55e' : '#cbd5e1',
                  cursor: 'pointer',
                  fontSize: '0.95rem',
                  fontWeight: activeSection === item.id ? '600' : '500',
                  transition: 'all 0.3s ease',
                  padding: '0.75rem 1rem',
                  borderRadius: '0.5rem',
                  textAlign: 'left'
                }}
              >
                {item.label}
              </button>
            ))}
            <a
              href="tel:+2250700000000"
              style={{
                padding: '0.75rem 1rem',
                background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                color: 'white',
                borderRadius: '0.5rem',
                textDecoration: 'none',
                fontSize: '0.95rem',
                fontWeight: '600',
                textAlign: 'center',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '0.5rem'
              }}
            >
              <Phone size={16} />
              Appeler l'Urgence
            </a>
          </div>
        </div>
      )}

      <style>{`
        @keyframes slideIn {
          from {
            width: 0;
          }
          to {
            width: 100%;
          }
        }

        @media (max-width: 768px) {
          nav {
            display: none !important;
          }
          
          .mobile-menu-btn {
            display: flex !important;
          }
          
          .mobile-nav {
            display: block !important;
          }
          
          a[href*="tel"] {
            display: none !important;
          }
        }
      `}</style>
    </header>
  )
}

export default Header