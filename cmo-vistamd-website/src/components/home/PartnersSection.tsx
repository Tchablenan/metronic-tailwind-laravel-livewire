import { useState, useEffect, useRef } from 'react'
import { ChevronLeft, ChevronRight, Building2 } from 'lucide-react'

const PartnersSection = () => {
  const [currentIndex, setCurrentIndex] = useState(0)
  const [isAutoplay, setIsAutoplay] = useState(true)
  const [isVisible, setIsVisible] = useState(false)
  const [hoveredCard, setHoveredCard] = useState<number | null>(null)
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768)
  const sectionRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768)
    window.addEventListener('resize', handleResize)
    return () => window.removeEventListener('resize', handleResize)
  }, [])

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        setIsVisible(entry.isIntersecting)
      },
      {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
      }
    )

    if (sectionRef.current) {
      observer.observe(sectionRef.current)
    }

    return () => {
      if (sectionRef.current) {
        observer.unobserve(sectionRef.current)
      }
    }
  }, [])

  const partners = [
    {
      id: 1,
      name: 'Massouk Logistique',
      logo: null, // Pas de logo - on va créer une représentation
      type: 'Logistique Médicale',
      initials: 'ML'
    },
    {
      id: 2,
      name: 'MAROIL SARL',
      logo: 'assets/images/partner/logo-maroil.png',
      type: 'Services Maritimes & Pétroliers'
    },
    {
      id: 3,
      name: 'Global Growths Motions',
      logo: 'assets/images/partner/Global growths Motions.png',
      type: 'Croissance & Développement'
    },
    {
      id: 4,
      name: 'Clinique Médicale Danga',
      logo: null,
      type: 'Établissement Médical',
      initials: 'CMD'
    },
    {
      id: 5,
      name: 'Polyclinique PISAM',
      logo: 'assets/images/partner/images.png',
      type: 'Polyclinique Internationale'
    },
    {
      id: 6,
      name: 'Polyclinique Farah',
      logo: 'assets/images/partner/Farah.png',
      type: 'Établissement de Santé'
    },
    {
      id: 7,
      name: 'Centre Médical le Rond Point',
      logo: 'assets/images/partner/centre-medical-rond-point.png',
      type: 'Centre Médical'
    },
    {
      id: 8,
      name: 'Centre Médical Vista Vision',
      logo: 'assets/images/partner/logo-rivisto.png',
      type: 'Centre d\'Ophtalmologie'
    },
    {
      id: 9,
      name: 'Clinique médico-Chirurgicale les Arcades',
      logo: null, // Pas de logo
      type: 'Clinique Médico-Chirurgicale',
      initials: 'CLA'
    },
    {
      id: 10,
      name: 'Centre d\'Imagerie Médicale d\'Abidjan',
      logo: 'assets/images/partner/Centre d’imagerie médicale d’Abidjan (CIMA.png',
      type: 'Centre d\'Imagerie - CIMA'
    },
    {
      id: 11,
      name: 'Laboratoire BioGroup Africa',
      logo: 'assets/images/partner/laboratoire BioGroup - Africa.png',
      type: 'Laboratoire d\'Analyses'
    },
    {
      id: 12,
      name: 'Polyclinique CEMEDIC',
      logo: 'assets/images/partner/polyclinique CEMEDIC.jpg',
      type: 'Polyclinique Spécialisée'
    },
    {
      id: 13,
      name: 'Clinique CMIDA',
      logo: 'assets/images/partner/clinique CMIDA.jpg',
      type: 'Clinique Médicale'
    }
  ]

  // Auto-slide carousel
  useEffect(() => {
    if (!isAutoplay) return

    const timer = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % partners.length)
    }, 4000)

    return () => clearInterval(timer)
  }, [isAutoplay, partners.length])

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev + 1) % partners.length)
    setIsAutoplay(false)
  }

  const prevSlide = () => {
    setCurrentIndex((prev) => (prev - 1 + partners.length) % partners.length)
    setIsAutoplay(false)
  }

  const goToSlide = (index: number) => {
    setCurrentIndex(index)
    setIsAutoplay(false)
  }

  // Affiche 3 logos à la fois sur desktop, 1 sur mobile
  const slidesToShow = isMobile ? 1 : 3
  const visibleSlides = Array.from({ length: slidesToShow }, (_, i) => 
    partners[(currentIndex + i) % partners.length]
  )

  return (
    <div 
      ref={sectionRef}
      id='partners' 
      style={{
        background: '#ffffff',
        padding: isMobile ? '60px 0' : '100px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      {/* Éléments de décoration animés */}
      <div style={{
        position: 'absolute',
        top: '5%',
        left: '-10%',
        width: isMobile ? '300px' : '500px',
        height: isMobile ? '300px' : '500px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '5%',
        right: '-10%',
        width: isMobile ? '300px' : '500px',
        height: isMobile ? '300px' : '500px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 2s'
      }}></div>

      {/* Particules décoratives */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        pointerEvents: 'none',
        zIndex: 1
      }}>
        {[...Array(6)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: '3px',
              height: '3px',
              background: i % 2 === 0 ? '#22c55e' : '#1075B9',
              borderRadius: '50%',
              top: `${15 + Math.random() * 70}%`,
              left: `${10 + Math.random() * 80}%`,
              opacity: 0.4,
              animation: `particleFloat ${8 + Math.random() * 4}s ease-in-out infinite ${Math.random() * 5}s`
            }}
          />
        ))}
      </div>

      <div style={{ 
        maxWidth: '1200px',
        margin: '0 auto',
        padding: isMobile ? '0 16px' : '0 20px',
        position: 'relative', 
        zIndex: 2 
      }}>
        {/* Header */}
        <div style={{
          textAlign: 'center',
          marginBottom: isMobile ? '2rem' : '3rem',
          maxWidth: '800px',
          margin: isMobile ? '0 auto 2rem' : '0 auto 4rem'
        }}>
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.5rem',
            marginBottom: '1rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'translateY(0)' : 'translateY(-30px)',
            transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s'
          }}>
            <Building2 
              size={isMobile ? 20 : 24} 
              style={{ 
                color: '#22c55e',
                animation: isVisible ? 'pulse 2s ease-in-out infinite' : 'none'
              }} 
            />
            <span style={{
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              WebkitBackgroundClip: 'text',
              WebkitTextFillColor: 'transparent',
              backgroundClip: 'text',
              fontWeight: '600',
              fontSize: isMobile ? '0.9rem' : '1rem',
              textTransform: 'uppercase',
              letterSpacing: '0.1em'
            }}>
              Nos Partenaires
            </span>
          </div>

          <h2 style={{
            fontSize: isMobile ? '1.75rem' : '3rem',
            fontWeight: 'bold',
            color: '#0f172a',
            lineHeight: '1.2',
            marginBottom: isMobile ? '1rem' : '1.5rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'scale(1)' : 'scale(0.9)',
            transition: 'opacity 0.6s ease-out 0.3s, transform 0.6s ease-out 0.3s'
          }}>
            Établissements et Partenaires de Confiance
          </h2>

          <p style={{
            fontSize: isMobile ? '1rem' : '1.15rem',
            color: '#64748b',
            lineHeight: '1.8',
            opacity: isVisible ? 1 : 0,
            transition: 'opacity 0.6s ease-out 0.4s'
          }}>
            CMO VISTAMD collabore avec les meilleurs établissements publics et privés pour vous offrir les meilleurs services médicaux
          </p>
        </div>

        {/* Carousel */}
        <div style={{
          maxWidth: '1000px',
          margin: '0 auto',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(50px)',
          transition: 'opacity 0.6s ease-out 0.5s, transform 0.6s ease-out 0.5s'
        }}>
          {/* Main Carousel Display */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: isMobile ? '1fr' : 'repeat(3, 1fr)',
            gap: isMobile ? '1.5rem' : '2rem',
            marginBottom: isMobile ? '2rem' : '3rem'
          }}>
            {visibleSlides.map((partner, index) => (
              <div
                key={partner.id}
                style={{
                  padding: isMobile ? '1.5rem' : '2rem',
                  background: '#ffffff',
                  border: hoveredCard === index ? '2px solid #22c55e' : '2px solid rgba(34, 197, 94, 0.1)',
                  borderRadius: '1rem',
                  display: 'flex',
                  flexDirection: 'column',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '1rem',
                  transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                  cursor: 'pointer',
                  minHeight: isMobile ? '200px' : '250px',
                  position: 'relative',
                  overflow: 'hidden',
                  opacity: isVisible ? 1 : 0,
                  transform: isVisible ? 'scale(1)' : 'scale(0.9)',
                  transitionDelay: `${0.6 + index * 0.1}s`
                }}
                onMouseEnter={() => setHoveredCard(index)}
                onMouseLeave={() => setHoveredCard(null)}
              >
                {/* Effet de brillance au survol */}
                <div style={{
                  position: 'absolute',
                  top: 0,
                  left: hoveredCard === index ? '0' : '-100%',
                  width: '100%',
                  height: '100%',
                  background: 'linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.1), transparent)',
                  transition: 'left 0.6s ease',
                  pointerEvents: 'none'
                }}></div>

                {/* Effet de vague au survol */}
                <div style={{
                  position: 'absolute',
                  top: '50%',
                  left: '50%',
                  width: hoveredCard === index ? '250%' : '0%',
                  height: hoveredCard === index ? '250%' : '0%',
                  background: 'radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)',
                  transform: 'translate(-50%, -50%)',
                  transition: 'all 0.6s ease',
                  borderRadius: '50%',
                  pointerEvents: 'none'
                }}></div>

                {/* Badge "Partenaire Officiel" */}
                <div style={{
                  position: 'absolute',
                  top: '10px',
                  right: '10px',
                  background: 'linear-gradient(135deg, #22c55e, #10b981)',
                  color: 'white',
                  fontSize: '0.65rem',
                  fontWeight: 'bold',
                  padding: '0.25rem 0.5rem',
                  borderRadius: '0.25rem',
                  textTransform: 'uppercase',
                  letterSpacing: '0.05em',
                  opacity: hoveredCard === index ? 1 : 0,
                  transform: hoveredCard === index ? 'translateY(0) scale(1)' : 'translateY(-10px) scale(0.8)',
                  transition: 'all 0.3s ease',
                  boxShadow: '0 4px 12px rgba(34, 197, 94, 0.3)'
                }}>
                  Partenaire
                </div>

                {/* Logo ou Initiales */}
                <div style={{
                  width: '150px',
                  height: '80px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  background: partner.logo ? 'rgba(34, 197, 94, 0.05)' : 'linear-gradient(135deg, #22c55e, #10b981)',
                  borderRadius: '0.75rem',
                  padding: '1rem',
                  transition: 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                  transform: hoveredCard === index ? 'scale(1.1) rotate(3deg)' : 'scale(1)',
                  position: 'relative',
                  zIndex: 1
                }}>
                  {partner.logo ? (
                    <img
                      src={partner.logo}
                      alt={partner.name}
                      style={{
                        maxWidth: '100%',
                        maxHeight: '100%',
                        objectFit: 'contain',
                        filter: hoveredCard === index ? 'brightness(1.1)' : 'brightness(1)',
                        transition: 'filter 0.3s ease'
                      }}
                    />
                  ) : (
                    // Affichage des initiales pour les partenaires sans logo
                    <div style={{
                      fontSize: '2rem',
                      fontWeight: 'bold',
                      color: 'white',
                      textShadow: '0 2px 10px rgba(0,0,0,0.2)',
                      letterSpacing: '0.1em'
                    }}>
                      {partner.initials}
                    </div>
                  )}
                </div>

                {/* Info */}
                <div style={{ 
                  textAlign: 'center',
                  position: 'relative',
                  zIndex: 1
                }}>
                  <h3 style={{
                    fontSize: isMobile ? '0.95rem' : '1.1rem',
                    fontWeight: 'bold',
                    color: hoveredCard === index ? '#22c55e' : '#0f172a',
                    marginBottom: '0.5rem',
                    transition: 'color 0.3s ease'
                  }}>
                    {partner.name}
                  </h3>
                  <p style={{
                    fontSize: isMobile ? '0.8rem' : '0.9rem',
                    color: '#64748b'
                  }}>
                    {partner.type}
                  </p>
                </div>

                {/* Checkmark au survol */}
                <div style={{
                  position: 'absolute',
                  bottom: '10px',
                  left: '10px',
                  width: '30px',
                  height: '30px',
                  background: 'linear-gradient(135deg, #22c55e, #10b981)',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: 'white',
                  fontSize: '1rem',
                  opacity: hoveredCard === index ? 1 : 0,
                  transform: hoveredCard === index ? 'scale(1) rotate(0deg)' : 'scale(0) rotate(-180deg)',
                  transition: 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                  boxShadow: '0 5px 15px rgba(34, 197, 94, 0.4)'
                }}>
                  ✓
                </div>
              </div>
            ))}
          </div>

          {/* Navigation Controls */}
          <div style={{
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            gap: isMobile ? '1rem' : '2rem',
            marginTop: '2rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
            transition: 'opacity 0.6s ease-out 0.9s, transform 0.6s ease-out 0.9s'
          }}>
            {/* Previous Button */}
            <button
              type="button"
              onClick={prevSlide}
              aria-label="Diapositif précédent"
              className="carousel-btn carousel-btn-prev"
              data-size={isMobile ? 'sm' : 'md'}
              onMouseEnter={(e) => {
                e.currentTarget.style.transform = 'scale(1.15) rotate(-10deg)'
                e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.4)'
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.transform = 'scale(1) rotate(0deg)'
                e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.2)'
              }}
            >
              <ChevronLeft size={isMobile ? 20 : 24} />
            </button>

            {/* Dots Navigation */}
            <div style={{
              display: 'flex',
              gap: '0.5rem',
              alignItems: 'center',
              flexWrap: 'wrap',
              justifyContent: 'center',
              maxWidth: isMobile ? '200px' : 'none'
            }}>
              {partners.map((_, index) => (
                <button
                  key={index}
                  type="button"
                  onClick={() => goToSlide(index)}
                  title={`Aller à la diapositive ${index + 1}`}
                  aria-label={`Aller à la diapositive ${index + 1}`}
                  className={`carousel-dot ${currentIndex === index ? 'carousel-dot-active' : ''}`}
                  data-mobile={isMobile}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.transform = 'scale(1.2)'
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.transform = 'scale(1)'
                  }}
                />
              ))}
            </div>

            {/* Next Button */}
            <button
              type="button"
              onClick={nextSlide}
              aria-label="Diapositif suivant"
              className="carousel-btn carousel-btn-next"
              data-size={isMobile ? 'sm' : 'md'}
              onMouseEnter={(e) => {
                e.currentTarget.style.transform = 'scale(1.15) rotate(10deg)'
                e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.4)'
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.transform = 'scale(1) rotate(0deg)'
                e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.2)'
              }}
            >
              <ChevronRight size={isMobile ? 20 : 24} />
            </button>
          </div>

          {/* Compteur de partenaires */}
          <div style={{
            textAlign: 'center',
            marginTop: '2rem',
            opacity: isVisible ? 1 : 0,
            transition: 'opacity 0.6s ease-out 1s'
          }}>
           
          </div>
        </div>
      </div>

      <style>{`
        @keyframes slideInDown {
          from {
            opacity: 0;
            transform: translateY(-30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes slideInUp {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes float {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(30px);
          }
        }

        @keyframes pulse {
          0%, 100% {
            opacity: 1;
            transform: scale(1);
          }
          50% {
            opacity: 0.8;
            transform: scale(1.1);
          }
        }

        @keyframes particleFloat {
          0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.4;
          }
          25% {
            opacity: 0.7;
          }
          50% {
            transform: translateY(-20px) translateX(10px);
            opacity: 0.4;
          }
          75% {
            opacity: 0.7;
          }
        }

        .carousel-btn {
          background: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
          border: none;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          cursor: pointer;
          transition: all 0.3s ease;
          box-shadow: 0 10px 25px rgba(34, 197, 94, 0.2);
        }

        .carousel-btn[data-size="sm"] {
          width: 40px;
          height: 40px;
        }

        .carousel-btn[data-size="md"] {
          width: 50px;
          height: 50px;
        }

        .carousel-btn:hover {
          box-shadow: 0 15px 35px rgba(34, 197, 94, 0.4);
        }

        .carousel-dot {
          height: 10px;
          border: none;
          border-radius: 50px;
          cursor: pointer;
          transition: all 0.3s ease;
          background: rgba(34, 197, 94, 0.2);
          padding: 0;
        }

        .carousel-dot[data-mobile="true"] {
          width: 10px;
        }

        .carousel-dot[data-mobile="false"] {
          width: 10px;
        }

        .carousel-dot-active {
          background: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
          box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .carousel-dot-active[data-mobile="true"] {
          width: 20px;
        }

        .carousel-dot-active[data-mobile="false"] {
          width: 30px;
        }
      `}</style>
    </div>
  )
}

export default PartnersSection