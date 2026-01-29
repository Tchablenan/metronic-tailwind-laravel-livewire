// src/components/home/FAQSection.tsx
import { useState, useEffect, useRef } from 'react'
import { ChevronDown, HelpCircle } from 'lucide-react'

const FAQSection = () => {
  const [openIndex, setOpenIndex] = useState<number | null>(0)
  const [isVisible, setIsVisible] = useState(false)
  const sectionRef = useRef<HTMLDivElement>(null)

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

  const faqs = [
    {
      id: 1,
      question: 'Quels sont les services offerts par CMO VISTAMD ?',
      answer: 'CMO VISTAMD offre une gamme complète de services incluant les services médicaux et paramédicaux, le transport médicalisé 24/7, les évacuations sanitaires, l\'assistance médicale, l\'importation/exportation de matériel biomédical, la maintenance d\'équipements, les partenariats avec les établissements publics et privés, et les formations professionnelles.'
    },
    {
      id: 2,
      question: 'Comment accéder au service d\'urgence 24/7 ?',
      answer: 'Vous pouvez contacter notre ligne d\'urgence 24/7 au +225 07 00 00 00 00. Notre équipe d\'urgence est disponible à tout moment pour répondre à vos besoins médicaux critiques et vous fournir une assistance immédiate.'
    },
    {
      id: 3,
      question: 'Comment est protégée ma confidentialité médicale ?',
      answer: 'CMO VISTAMD respecte strictement la confidentialité de vos données médicales. Toutes les informations sont protégées selon les normes de sécurité les plus strictes et ne sont utilisées que pour fournir les services médicaux appropriés.'
    },
    {
      id: 4,
      question: 'Proposez-vous des services de transport médicalisé ?',
      answer: 'Oui, nous proposons un service complet de transport médicalisé avec des ambulances équipées et du personnel formé. Notre service est disponible 24/7 pour tous types de transport médical, qu\'il soit urgent ou programmé.'
    },
    {
      id: 5,
      question: 'Comment prendre rendez-vous pour une consultation ?',
      answer: 'Vous pouvez prendre rendez-vous en utilisant notre formulaire en ligne sur le site, en nous appelant directement au +225 07 00 00 00 00, ou en visitant notre établissement à Grand-Bassam. Notre équipe vous aidera à trouver l\'horaire qui vous convient.'
    },
    {
      id: 6,
      question: 'Offrez-vous des formations en gestion de matériel médical ?',
      answer: 'Oui, CMO VISTAMD propose des formations professionnelles et du consulting en gestion de matériel et dispositifs médicaux. Contactez notre département formation pour connaître les prochaines sessions disponibles.'
    }
  ]

  return (
    <div 
      ref={sectionRef}
      id='faq' 
      className="rts-faq-area rts-section-gap" 
      style={{
        background: 'linear-gradient(180deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%)',
        padding: '100px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      {/* Éléments de décoration animés avec fond coloré */}
      <div style={{
        position: 'absolute',
        top: '10%',
        right: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '10%',
        left: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.15) 0%, rgba(16, 117, 185, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 2s'
      }}></div>

      {/* SVG Question Mark - Top Right */}
      <svg 
        style={{
          position: 'absolute',
          top: '8%',
          right: '6%',
          width: '150px',
          height: '150px',
          opacity: 0.45,
          animation: 'float 8s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 12px rgba(34, 197, 94, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="50" r="45" fill="rgba(34, 197, 94, 0.1)" stroke="#22c55e" strokeWidth="4"/>
        <text x="50" y="70" fontSize="60" fontWeight="bold" fill="#22c55e" textAnchor="middle">?</text>
        <circle cx="50" cy="50" r="35" fill="none" stroke="#10b981" strokeWidth="2" opacity="0.5"/>
      </svg>

      {/* SVG Document Icon - Top Left */}
      <svg 
        style={{
          position: 'absolute',
          top: '12%',
          left: '6%',
          width: '130px',
          height: '130px',
          opacity: 0.4,
          animation: 'float 9s ease-in-out infinite 1s',
          filter: 'drop-shadow(0 6px 14px rgba(16, 117, 185, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="25" y="15" width="50" height="70" rx="4" fill="#1075B9"/>
        <rect x="25" y="15" width="50" height="70" rx="4" fill="none" stroke="#0ea5e9" strokeWidth="2"/>
        <line x1="35" y1="30" x2="65" y2="30" stroke="white" strokeWidth="3" strokeLinecap="round"/>
        <line x1="35" y1="40" x2="65" y2="40" stroke="white" strokeWidth="3" strokeLinecap="round"/>
        <line x1="35" y1="50" x2="55" y2="50" stroke="white" strokeWidth="3" strokeLinecap="round"/>
        <line x1="35" y1="60" x2="65" y2="60" stroke="white" strokeWidth="2.5" strokeLinecap="round" opacity="0.7"/>
        <line x1="35" y1="68" x2="60" y2="68" stroke="white" strokeWidth="2.5" strokeLinecap="round" opacity="0.7"/>
        <circle cx="75" cy="25" r="8" fill="#22c55e"/>
        <path d="M72 25 L74 27 L78 23" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/>
      </svg>

      {/* SVG Light Bulb - Bottom Right */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '10%',
          right: '8%',
          width: '120px',
          height: '120px',
          opacity: 0.4,
          animation: 'pulse 3s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 14px rgba(251, 191, 36, 0.3))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="45" r="20" fill="#fbbf24" opacity="0.3"/>
        <path d="M50,25 Q35,35 35,50 Q35,60 40,65 L60,65 Q65,60 65,50 Q65,35 50,25 Z" fill="#fbbf24"/>
        <rect x="42" y="65" width="16" height="8" rx="2" fill="#f59e0b"/>
        <rect x="45" y="73" width="10" height="4" rx="2" fill="#d97706"/>
        <line x1="50" y1="10" x2="50" y2="20" stroke="#fbbf24" strokeWidth="3" strokeLinecap="round"/>
        <line x1="25" y1="25" x2="32" y2="32" stroke="#fbbf24" strokeWidth="3" strokeLinecap="round"/>
        <line x1="75" y1="25" x2="68" y2="32" stroke="#fbbf24" strokeWidth="3" strokeLinecap="round"/>
        <line x1="20" y1="45" x2="30" y2="45" stroke="#fbbf24" strokeWidth="3" strokeLinecap="round"/>
        <line x1="70" y1="45" x2="80" y2="45" stroke="#fbbf24" strokeWidth="3" strokeLinecap="round"/>
      </svg>

      {/* SVG Chat Bubble - Bottom Left */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '15%',
          left: '5%',
          width: '140px',
          height: '140px',
          opacity: 0.35,
          animation: 'float 10s ease-in-out infinite 2s',
          filter: 'drop-shadow(0 6px 14px rgba(34, 197, 94, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="20" y="25" width="60" height="45" rx="8" fill="#22c55e"/>
        <path d="M35,70 L40,80 L45,70 Z" fill="#22c55e"/>
        <circle cx="35" cy="47" r="4" fill="white"/>
        <circle cx="50" cy="47" r="4" fill="white"/>
        <circle cx="65" cy="47" r="4" fill="white"/>
        <rect x="20" y="25" width="60" height="45" rx="8" fill="none" stroke="#10b981" strokeWidth="2"/>
      </svg>

      {/* SVG Book Icon - Middle Right */}
      <svg 
        style={{
          position: 'absolute',
          top: '45%',
          right: '3%',
          width: '100px',
          height: '100px',
          opacity: 0.3,
          animation: 'float 11s ease-in-out infinite 3s',
          filter: 'drop-shadow(0 4px 10px rgba(16, 117, 185, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="25" y="20" width="50" height="60" rx="3" fill="#1075B9"/>
        <rect x="30" y="20" width="40" height="60" rx="2" fill="#0ea5e9"/>
        <line x1="50" y1="20" x2="50" y2="80" stroke="#1075B9" strokeWidth="2"/>
        <line x1="35" y1="35" x2="45" y2="35" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="35" y1="45" x2="45" y2="45" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="55" y1="35" x2="65" y2="35" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="55" y1="45" x2="65" y2="45" stroke="white" strokeWidth="2" strokeLinecap="round"/>
      </svg>

      {/* SVG Info Circle - Top Center */}
      <svg 
        style={{
          position: 'absolute',
          top: '5%',
          left: '50%',
          transform: 'translateX(-50%)',
          width: '90px',
          height: '90px',
          opacity: 0.35,
          animation: 'pulse 4s ease-in-out infinite',
          filter: 'drop-shadow(0 4px 8px rgba(34, 197, 94, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="50" r="40" fill="rgba(34, 197, 94, 0.15)" stroke="#22c55e" strokeWidth="4"/>
        <text x="50" y="70" fontSize="50" fontWeight="bold" fill="#22c55e" textAnchor="middle">i</text>
      </svg>

      {/* Particules décoratives colorées */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        pointerEvents: 'none',
        zIndex: 1
      }}>
        {[...Array(10)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: i % 3 === 0 ? '7px' : '4px',
              height: i % 3 === 0 ? '7px' : '4px',
              background: i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#fbbf24',
              borderRadius: '50%',
              top: `${15 + Math.random() * 70}%`,
              left: `${10 + Math.random() * 80}%`,
              opacity: 0.5,
              boxShadow: `0 0 8px ${i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#fbbf24'}`,
              animation: `particleFloat ${8 + Math.random() * 4}s ease-in-out infinite ${Math.random() * 5}s`
            }}
          />
        ))}
      </div>

      <div className="container" style={{ position: 'relative', zIndex: 2 }}>
        {/* Header */}
        <div style={{
          textAlign: 'center',
          marginBottom: '3rem',
          maxWidth: '800px',
          margin: '0 auto 4rem'
        }}>
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.5rem',
            marginBottom: '1rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'translateY(0)' : 'translateY(-30px)',
            transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s',
            background: 'rgba(34, 197, 94, 0.15)',
            padding: '0.6rem 1.2rem',
            borderRadius: '2rem',
            boxShadow: '0 4px 12px rgba(34, 197, 94, 0.2)'
          }}>
            <HelpCircle 
              size={22} 
              style={{ 
                color: '#22c55e',
                animation: isVisible ? 'pulse 2s ease-in-out infinite' : 'none'
              }} 
            />
            <span style={{
              color: '#22c55e',
              fontWeight: '700',
              fontSize: '0.9rem',
              textTransform: 'uppercase',
              letterSpacing: '0.1em'
            }}>
              Questions Fréquemment Posées
            </span>
          </div>

          <h2 style={{
            fontSize: '3rem',
            fontWeight: 'bold',
            color: '#0f172a',
            lineHeight: '1.2',
            marginBottom: '1.5rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'scale(1)' : 'scale(0.9)',
            transition: 'opacity 0.6s ease-out 0.3s, transform 0.6s ease-out 0.3s'
          }}>
            Trouvez les Réponses à Vos Questions
          </h2>

          <p style={{
            fontSize: '1.15rem',
            color: '#64748b',
            lineHeight: '1.8',
            opacity: isVisible ? 1 : 0,
            transition: 'opacity 0.6s ease-out 0.4s'
          }}>
            Consultez notre section FAQ pour obtenir rapidement les informations sur nos services, horaires et procédures
          </p>
        </div>

        {/* FAQ Accordion */}
        <div style={{
          maxWidth: '900px',
          margin: '0 auto'
        }}>
          {faqs.map((faq, index) => (
            <div
              key={faq.id}
              style={{
                marginBottom: '1rem',
                opacity: isVisible ? 1 : 0,
                transform: isVisible ? 'translateY(0) scale(1)' : 'translateY(50px) scale(0.95)',
                transition: `opacity 0.6s ease-out ${0.5 + index * 0.1}s, transform 0.6s ease-out ${0.5 + index * 0.1}s`
              }}
            >
              <div
                onClick={() => setOpenIndex(openIndex === index ? null : index)}
                style={{
                  padding: '1.5rem',
                  background: openIndex === index 
                    ? 'linear-gradient(135deg, rgba(34, 197, 94, 0.12) 0%, rgba(16, 117, 185, 0.08) 100%)'
                    : '#ffffff',
                  border: openIndex === index 
                    ? '2px solid #22c55e' 
                    : '2px solid rgba(34, 197, 94, 0.15)',
                  borderRadius: '1rem',
                  cursor: 'pointer',
                  transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  gap: '1rem',
                  position: 'relative',
                  overflow: 'hidden',
                  boxShadow: openIndex === index ? '0 10px 30px rgba(34, 197, 94, 0.2)' : '0 4px 12px rgba(0, 0, 0, 0.05)'
                }}
                onMouseEnter={(e) => {
                  if (openIndex !== index) {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.4)'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.05)'
                    e.currentTarget.style.transform = 'translateX(5px)'
                  } else {
                    e.currentTarget.style.transform = 'scale(1.01)'
                  }
                  e.currentTarget.style.boxShadow = '0 10px 30px rgba(34, 197, 94, 0.2)'
                }}
                onMouseLeave={(e) => {
                  if (openIndex !== index) {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.15)'
                    e.currentTarget.style.background = '#ffffff'
                  }
                  e.currentTarget.style.transform = 'translateX(0) scale(1)'
                  e.currentTarget.style.boxShadow = openIndex === index ? '0 10px 30px rgba(34, 197, 94, 0.2)' : '0 4px 12px rgba(0, 0, 0, 0.05)'
                }}
              >
                {/* Effet de vague au clic */}
                <div style={{
                  position: 'absolute',
                  top: '50%',
                  left: '50%',
                  width: openIndex === index ? '200%' : '0%',
                  height: openIndex === index ? '200%' : '0%',
                  background: 'radial-gradient(circle, rgba(34, 197, 94, 0.12) 0%, transparent 70%)',
                  transform: 'translate(-50%, -50%)',
                  transition: 'all 0.6s ease',
                  borderRadius: '50%',
                  pointerEvents: 'none'
                }}></div>

                {/* Numéro de question */}
                <div style={{
                  position: 'absolute',
                  left: '-10px',
                  top: '-10px',
                  width: '40px',
                  height: '40px',
                  background: openIndex === index 
                    ? 'linear-gradient(135deg, #22c55e, #10b981)' 
                    : 'rgba(34, 197, 94, 0.2)',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '0.9rem',
                  fontWeight: 'bold',
                  color: openIndex === index ? 'white' : '#22c55e',
                  transition: 'all 0.3s ease',
                  boxShadow: openIndex === index ? '0 5px 15px rgba(34, 197, 94, 0.4)' : 'none'
                }}>
                  {index + 1}
                </div>

                <h3 style={{
                  fontSize: '1.1rem',
                  fontWeight: '600',
                  margin: 0,
                  flex: 1,
                  transition: 'color 0.3s ease',
                  color: openIndex === index ? '#22c55e' : '#0f172a',
                  paddingLeft: '35px',
                  position: 'relative',
                  zIndex: 1
                }}>
                  {faq.question}
                </h3>
                <ChevronDown
                  size={24}
                  style={{
                    color: openIndex === index ? '#22c55e' : '#64748b',
                    transition: 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                    transform: openIndex === index ? 'rotate(180deg) scale(1.2)' : 'rotate(0) scale(1)',
                    flexShrink: 0,
                    position: 'relative',
                    zIndex: 1
                  }}
                />
              </div>

              {/* Answer - Animated collapse */}
              {openIndex === index && (
                <div
                  style={{
                    padding: '1.5rem',
                    background: 'rgba(34, 197, 94, 0.08)',
                    borderLeft: '4px solid #22c55e',
                    marginTop: '-1px',
                    borderRadius: '0 0 1rem 1rem',
                    animation: 'slideInUp 0.3s ease-out, fadeIn 0.3s ease-out',
                    position: 'relative',
                    overflow: 'hidden'
                  }}
                >
                  {/* Icône décorative */}
                  <div style={{
                    position: 'absolute',
                    right: '20px',
                    top: '20px',
                    fontSize: '3rem',
                    opacity: 0.15,
                    animation: 'float 3s ease-in-out infinite'
                  }}>
                    💡
                  </div>

                  <p style={{
                    fontSize: '1rem',
                    color: '#64748b',
                    lineHeight: '1.8',
                    margin: 0,
                    position: 'relative',
                    zIndex: 1
                  }}>
                    {faq.answer}
                  </p>
                </div>
              )}
            </div>
          ))}
        </div>

        {/* Bottom CTA */}
        <div style={{
          textAlign: 'center',
          marginTop: '3rem',
          padding: '2.5rem 2rem',
          background: '#ffffff',
          borderRadius: '1.5rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
          transition: 'opacity 0.6s ease-out 1.2s, transform 0.6s ease-out 1.2s',
          position: 'relative',
          overflow: 'hidden',
          border: '2px solid rgba(34, 197, 94, 0.15)',
          boxShadow: '0 10px 30px rgba(0, 0, 0, 0.08)'
        }}>
          {/* Effet de brillance */}
          <div style={{
            position: 'absolute',
            top: 0,
            left: '-100%',
            width: '100%',
            height: '100%',
            background: 'linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.15), transparent)',
            animation: isVisible ? 'shimmer 3s ease-in-out infinite' : 'none'
          }}></div>

          <p style={{
            fontSize: '1.2rem',
            color: '#0f172a',
            marginBottom: '1rem',
            fontWeight: '700',
            position: 'relative',
            zIndex: 1
          }}>
            Vous n'avez pas trouvé votre réponse ?
          </p>
          <p style={{
            fontSize: '1rem',
            color: '#64748b',
            marginBottom: '1.5rem',
            position: 'relative',
            zIndex: 1
          }}>
            Contactez-nous directement pour obtenir de l'aide personnalisée
          </p>
          <button
            style={{
              padding: '1rem 2.5rem',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              color: 'white',
              fontWeight: 'bold',
              border: 'none',
              borderRadius: '0.75rem',
              fontSize: '1rem',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)',
              position: 'relative',
              zIndex: 1
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'translateY(-3px) scale(1.05)'
              e.currentTarget.style.boxShadow = '0 15px 45px rgba(34, 197, 94, 0.5)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'translateY(0) scale(1)'
              e.currentTarget.style.boxShadow = '0 10px 30px rgba(34, 197, 94, 0.3)'
            }}
          >
            Nous Contacter →
          </button>
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

        @keyframes fadeIn {
          from {
            opacity: 0;
          }
          to {
            opacity: 1;
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

        @keyframes shimmer {
          0% {
            left: -100%;
          }
          100% {
            left: 200%;
          }
        }

        @keyframes particleFloat {
          0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.5;
          }
          25% {
            opacity: 0.8;
          }
          50% {
            transform: translateY(-20px) translateX(10px);
            opacity: 0.5;
          }
          75% {
            opacity: 0.8;
          }
        }
      `}</style>
    </div>
  )
}

export default FAQSection