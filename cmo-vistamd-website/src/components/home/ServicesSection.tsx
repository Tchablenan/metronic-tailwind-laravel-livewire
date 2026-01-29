import { useState, useEffect, useRef } from 'react'
import { 
  Heart, 
  Ambulance, 
  Package, 
  GraduationCap, 
  Users,
  Clock,
  Shield,
  Activity,
  Home,
  ChevronRight
} from 'lucide-react'

// Composant pour l'animation typewriter en boucle continue
const AnimatedDescription = ({ text, isMobile }: { text: string; isMobile: boolean }) => {
  const [displayedText, setDisplayedText] = useState('')
  const [currentIndex, setCurrentIndex] = useState(0)
  const [isDeleting, setIsDeleting] = useState(false)
  
  useEffect(() => {
    

    const typingSpeed = isDeleting ? 30 : 50 // Vitesse d'écriture/suppression
    const pauseEnd = 2000 // Pause à la fin avant de recommencer
    const pauseStart = 500 // Petite pause au début
    
    const timer = setTimeout(() => {
      if (!isDeleting && currentIndex < text.length) {
        // Mode écriture
        setDisplayedText(text.substring(0, currentIndex + 1))
        setCurrentIndex(currentIndex + 1)
      } else if (!isDeleting && currentIndex === text.length) {
        // Fin de l'écriture, pause puis commence à supprimer
        setTimeout(() => setIsDeleting(true), pauseEnd)
      } else if (isDeleting && currentIndex > 0) {
        // Mode suppression
        setDisplayedText(text.substring(0, currentIndex - 1))
        setCurrentIndex(currentIndex - 1)
      } else if (isDeleting && currentIndex === 0) {
        // Fin de suppression, recommence
        setIsDeleting(false)
        setTimeout(() => {
          setCurrentIndex(0)
          setDisplayedText('')
        }, pauseStart)
      }
    }, typingSpeed)
    
    return () => clearTimeout(timer)
  }, [currentIndex, isDeleting, text, isMobile])
  
  return (
    <p style={{
      fontSize: isMobile ? '1.1rem' : '2rem',
      color: '#137F02',
      lineHeight: '1.6',
      maxWidth: '900px',
      margin: '0 auto',
      minHeight: isMobile ? '60px' : '75px',
      position: 'relative',
      fontWeight: '500',
    }}>
      {displayedText}
      {!isMobile && (
        <span style={{
          display: 'inline-block',
          width: '3px',
          height: '1.2em',
          background: '#22c55e',
          marginLeft: '3px',
          animation: 'blink 0.8s infinite',
          verticalAlign: 'middle',
        }} />
      )}
    </p>
  )
}

const ServicesSection = () => {
  const [hoveredService, setHoveredService] = useState<number | null>(null)
  const [isVisible, setIsVisible] = useState(false)
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768)
  const sectionRef = useRef(null)

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768)
    window.addEventListener('resize', handleResize)
    return () => window.removeEventListener('resize', handleResize)
  }, [])

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => setIsVisible(entry.isIntersecting),
      { threshold: 0.1 }
    )
    if (sectionRef.current) observer.observe(sectionRef.current)
    
    // Force visible sur mobile ou si pas d'observer
    setIsVisible(true)
    
    return () => {
      if (sectionRef.current) observer.unobserve(sectionRef.current)
    }
  }, [])

  const mainService = {
    title: 'Services Médicaux et Paramédicaux',
    subtitle: 'Prestations Multidisciplinaires de Qualité',
    description: 'Nous sommes spécialiser dans la prise en charge des pathologies cardiovasculaires.',
    highlights: [
      {
        icon: Users,
        title: '50+ Professionnels',
        desc: 'Médecins et infirmiers',
        image: 'https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=400&q=80'
      },
      {
        icon: Clock,
        title: 'Disponibilité 24/7',
        desc: 'Service d\'urgence permanent',
        image: 'https://images.unsplash.com/photo-1584982751601-97dcc096659c?w=400&q=80'
      },
      {
        icon: Shield,
        title: 'Équipements Modernes',
        desc: 'Technologie pointe',
        image: 'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?w=400&q=80'
      }
    ],
    categories: [
      {
        id: 'explorations',
        icon: Activity,
        title: 'Explorations Cardiovasculaires',
        description: 'Technologies dernière génération pour diagnostics précis et fiables.',
        image: 'assets/stethoscope-frame-with-heart.jpg',
        color: '#22c55e',
        stats: {
          patients: '500+',
          label1: 'Examens/mois',
          satisfaction: '99%',
          label2: 'Précision',
          experience: '24-48h',
          label3: 'Résultats'
        },
        services: [
          {
            name: 'Consultation cardiologique pédiatrique et adulte',
            desc: 'Rendez-vous complet avec nos cardiologues certifiés pour bilan, diagnostic et suivi des maladies cardiaques chez enfants et adultes'
          },
          {
            name: 'Échographies cardiaques (ETT et ETO)',
            desc: 'Imagerie ultrasonore détaillée de votre cœur pour évaluer la fonction cardiaque, valves et cavités avec précision'
          },
          {
            name: 'Électrocardiogramme (ECG)',
            desc: 'Enregistrement de l\'activité électrique du cœur au repos pour détecter troubles du rythme et anomalies'
          },
          {
            name: 'Holters ECG et Tensionnels 24-48h',
            desc: 'Surveillance continue ambulatoire de votre rythme cardiaque et tension artérielle sur 24 à 48 heures'
          },
          {
            name: 'Épreuve d\'effort sur tapis roulant',
            desc: 'Test sur tapis roulant avec monitoring ECG pour évaluer la capacité cardiaque à l\'effort et détecter l\'ischémie'
          },
          {
            name: 'Spirométrie et tests respiratoires',
            desc: 'Mesure complète de la capacité respiratoire et fonction pulmonaire avec courbes débit-volume'
          },
          {
            name: 'Audiométrie tonale et vocale',
            desc: 'Bilan auditif complet pour évaluation de l\'audition avec audiogramme détaillé'
          },
          {
            name: 'Échodoppler vasculaire des membres',
            desc: 'Examen ultrasonore des veines et artères des bras et jambes pour détecter thromboses et sténoses'
          },
          {
            name: 'Échodoppler des troncs supra-aortiques',
            desc: 'Vérification de la circulation des artères carotides et vertébrales irriguant le cerveau'
          },
          {
            name: 'Coronarographie diagnostique',
            desc: 'Visualisation par rayons X des artères coronaires avec injection de produit de contraste iodé'
          },
          {
            name: 'Angioplastie coronaire avec stent',
            desc: 'Déblocage des artères cardiaques rétrécies par ballonnet et pose de stent pour maintenir l\'ouverture'
          },
          {
            name: 'Artériographie périphérique',
            desc: 'Radiographie des artères périphériques avec produit de contraste pour cartographier les sténoses'
          },
          {
            name: 'Implantation de Pacemaker et Défibrillateur',
            desc: 'Pose de stimulateurs cardiaques (pacemaker) ou défibrillateurs implantables pour troubles du rythme sévères'
          }
        ]
      },
      {
        id: 'paramedicaux',
        icon: Heart,
        title: 'Nous pouvons aussi prendre soin de vous à Domicile',
        description: 'Soins de qualité par infirmiers expérimentés au centre ou à domicile.',
        image: 'assets/doctor-reading.jpg',
        color: '#1075B9',
        stats: {
          patients: '300+',
          label1: 'Patients suivis',
          satisfaction: '98%',
          label2: 'Satisfaction',
          experience: '15+ ans',
          label3: 'Expérience'
        },
        subcategories: [
          {
            icon: Home,
            title: 'Ce que nous vous apportons à domicile',
            image: 'assets/soin-domicile.jpg',
            services: [
              {
                name: 'Nursing',
                desc: 'Soins infirmiers complets à votre domicile : pansements, injections, perfusions et suivi médical quotidien'
              },
              {
                name: 'Aide patients à domicile',
                desc: 'Assistance complète dans les gestes du quotidien, toilette, habillage et installation confortable au lit ou fauteuil'
              },
              {
                name: 'Rééducation',
                desc: 'Programme de rééducation post-opératoire avec thérapeute pour une récupération optimale après chirurgie'
              },
              {
                name: 'Kinésithérapie',
                desc: 'Séances de rééducation motrice et articulaire pour retrouver mobilité et autonomie avec exercices adaptés'
              },
              {
                name: 'Mobilisation des patients en situation d\'handicap moteur',
                desc: 'Accompagnement spécialisé et transferts sécurisés pour patients à mobilité réduite avec respect de l\'intégrité physique'
              }
            ]
          }
        ]
      }
    ]
  }

  // ✅ VOICI LES 4 NOUVEAUX SERVICES COMME TU L'AS DEMANDÉ
  const otherServices = [
    {
      id: 1,
      icon: Ambulance,
      title: 'Transport Médicalisé et Évacuation Sanitaire',
      description: 'Service disponible 24h/24 pour le transport médical sécurisé et l\'évacuation sanitaire vers des structures partenaires partout en Côte d\'Ivoire et à l\'international. Nos ambulances de dernière génération sont équipées avec du matériel de pointe et accompagnées de spécialistes qualifiés pour garantir votre sécurité durant le transfert.',
      color: '#22c55e'
    },
    {
      id: 2,
      icon: Home,
      title: 'Assistance Médicale à Domicile',
      description: 'Accompagnement médical professionnel à domicile avec nursing, soins infirmiers, aide aux patients, kinésithérapie et rééducation. Nos équipes interviennent chez vous pour vous offrir des soins de qualité dans le confort de votre foyer avec un suivi personnalisé et adapté à vos besoins spécifiques.',
      color: '#1075B9'
    },
    {
      id: 3,
      icon: Package,
      title: 'Distribution et Maintenance Biomédicale',
      description: 'Distribution de consommables et équipements biomédicaux de haute qualité dans toutes les spécialités médicales. Maintenance préventive et corrective, réparation des équipements médicaux de toutes disciplines médico-chirurgicales. Service après-vente et assistance technique assurés par des techniciens certifiés.',
      color: '#22c55e'
    },
    {
      id: 4,
      icon: GraduationCap,
      title: 'Formation Conseil en Santé & Partenariat Public-Privé',
      description: 'Formations professionnelles et conseils en gestion de matériel et dispositifs médicaux. Mise en place de partenariats stratégiques avec les établissements publics et privés de santé (EPPH). Accompagnement dans la structuration de projets de santé et transfert de compétences aux équipes médicales.',
      color: '#1075B9'
    }
  ]

  const features = [
    { icon: Clock, text: 'Disponible 24/7', color: '#22c55e' },
    { icon: Shield, text: 'Service Sécurisé', color: '#1075B9' },
    { icon: Heart, text: 'Qualité Garantie', color: '#22c55e' },
    { icon: Users, text: 'Équipe Expérimentée', color: '#1075B9' }
  ]

  return (
    <div 
      ref={sectionRef}
      id='services'
      style={{
        background: '#ffffff',
        padding: isMobile ? '24px 0' : '40px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      <div style={{
        position: 'absolute',
        top: isMobile ? '5%' : '10%',
        left: isMobile ? '-10%' : '-5%',
        width: isMobile ? '200px' : '300px',
        height: isMobile ? '200px' : '300px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite'
      }}></div>

      <div style={{
        maxWidth: '1200px',
        margin: '0 auto',
        padding: isMobile ? '0 12px' : '0 20px',
        position: 'relative',
        zIndex: 4
      }}>
        {/* HEADER */}
        <div style={{
          textAlign: 'center',
          marginBottom: isMobile ? '24px' : '32px'
        }}>
          <span style={{
            display: 'inline-block',
            background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
            WebkitBackgroundClip: 'text',
            WebkitTextFillColor: 'transparent',
            backgroundClip: 'text',
            fontWeight: '600',
            fontSize: isMobile ? '1.75rem' : '2rem',
            marginBottom: '8px',
            textTransform: 'uppercase',
            letterSpacing: '0.05em'
          }}>
            Nos Services
          </span>

          <h2 style={{
            fontSize: isMobile ? '1.5rem' : '2rem',
            fontWeight: 'bold',
            color: '#0f172a',
            lineHeight: '1.2',
            margin: '0 0 12px 0'
          }}>
            Prestations Multidisciplinaires de Qualité
          </h2>

          
        </div>

        {/* MAIN SERVICE BOX */}
        <div style={{
          background: 'linear-gradient(135deg, rgba(34, 197, 94, 0.08) 0%, rgba(16, 117, 185, 0.08) 100%)',
          border: '3px solid #22c55e',
          borderRadius: isMobile ? '12px' : '16px',
          padding: isMobile ? '16px' : '32px',
          position: 'relative',
          overflow: 'hidden',
          backdropFilter: 'blur(10px)',
          marginBottom: isMobile ? '32px' : '48px',
          opacity: isMobile ? 1 : (isVisible ? 1 : 0),
          transform: isMobile ? 'translateY(0)' : (isVisible ? 'translateY(0)' : 'translateY(50px)'),
          transition: isMobile ? 'none' : 'opacity 0.8s ease-out 0.5s, transform 0.8s ease-out 0.5s',
        }}>
          <div style={{
            textAlign: 'center',
            marginBottom: isMobile ? '20px' : '28px',
          }}>
            <div style={{
              display: 'inline-flex',
              alignItems: 'center',
              justifyContent: 'center',
              width: isMobile ? '60px' : '80px',
              height: isMobile ? '60px' : '80px',
              background: 'linear-gradient(135deg, #22c55e, #10b981)',
              borderRadius: '12px',
              marginBottom: '12px',
              boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)',
            }}>
              <Heart size={isMobile ? 28 : 36} color="white" />
            </div>

            <AnimatedDescription 
              text={mainService.description}
              isMobile={isMobile}
            />
          </div>

          {/* HIGHLIGHTS */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: isMobile ? '1fr' : 'repeat(auto-fit, minmax(250px, 1fr))',
            gap: isMobile ? '12px' : '16px',
            marginBottom: isMobile ? '20px' : '28px',
          }}>
            {mainService.highlights.map((highlight, idx) => {
              const Icon = highlight.icon
              return (
                <div key={idx} style={{
                  position: 'relative',
                  minHeight: isMobile ? '120px' : '150px',
                  borderRadius: '12px',
                  overflow: 'hidden',
                  boxShadow: '0 4px 15px rgba(0,0,0,0.1)',
                }}>
                  <div style={{
                    position: 'absolute',
                    inset: 0,
                    backgroundImage: `url(${highlight.image})`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                  }}></div>

                  <div style={{
                    position: 'absolute',
                    inset: 0,
                    background: 'linear-gradient(135deg, rgba(34, 197, 94, 0.85), rgba(16, 117, 185, 0.85))',
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                    justifyContent: 'center',
                    padding: '16px',
                    textAlign: 'center',
                  }}>
                    <Icon size={isMobile ? 28 : 36} color="white" style={{ marginBottom: '8px' }} />
                    <h5 style={{
                      fontSize: isMobile ? '0.95rem' : '1.1rem',
                      fontWeight: 'bold',
                      color: 'white',
                      margin: 0,
                      marginBottom: '4px',
                    }}>
                      {highlight.title}
                    </h5>
                    <p style={{
                      fontSize: isMobile ? '0.75rem' : '0.85rem',
                      color: 'white',
                      opacity: 0.95,
                      margin: 0,
                    }}>
                      {highlight.desc}
                    </p>
                  </div>
                </div>
              )
            })}
          </div>

          {/* CATEGORIES */}
          <div style={{
            display: 'flex',
            flexDirection: 'column',
            gap: isMobile ? '12px' : '20px',
            marginTop: isMobile ? '16px' : '24px',
          }}>
            {mainService.categories.map((category) => {
              const Icon = category.icon
              return (
                <div 
                  key={category.id}
                  style={{
                    background: '#ffffff',
                    borderRadius: '12px',
                    overflow: 'hidden',
                    border: `2px solid ${category.color}20`,
                    boxShadow: '0 4px 20px rgba(0, 0, 0, 0.08)',
                  }}
                >
                  <div style={{
                    minHeight: isMobile ? '100px' : '140px',
                    backgroundImage: `url(${category.image})`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    position: 'relative',
                  }}>
                    <div style={{
                      position: 'absolute',
                      bottom: 0,
                      left: 0,
                      right: 0,
                      background: `linear-gradient(to top, ${category.color}, transparent)`,
                      padding: isMobile ? '12px 12px 8px' : '16px 12px 12px',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '8px',
                    }}>
                      <div style={{
                        width: isMobile ? '40px' : '50px',
                        height: isMobile ? '40px' : '50px',
                        background: 'rgba(255, 255, 255, 0.95)',
                        borderRadius: '8px',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        boxShadow: '0 4px 15px rgba(0, 0, 0, 0.2)',
                        flexShrink: 0,
                      }}>
                        <Icon size={isMobile ? 20 : 24} color={category.color} />
                      </div>
                      <h4 style={{
                        fontSize: isMobile ? '0.95rem' : '1.15rem',
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0 2px 10px rgba(0, 0, 0, 0.3)',
                        flex: 1,
                        margin: 0,
                      }}>
                        {category.title}
                      </h4>
                    </div>
                  </div>

                  <div style={{
                    padding: isMobile ? '16px' : '20px',
                  }}>
                    <p style={{
                      fontSize: isMobile ? '0.85rem' : '0.95rem',
                      color: '#64748b',
                      lineHeight: '1.6',
                      margin: '0 0 12px 0',
                    }}>
                      {category.description}
                    </p>

                    {/* STATS */}
                    <div style={{
                      display: 'grid',
                      gridTemplateColumns: isMobile ? '1fr' : 'repeat(3, 1fr)',
                      gap: isMobile ? '8px' : '12px',
                      marginBottom: '12px',
                      padding: '12px',
                      background: `${category.color}15`,
                      borderRadius: '8px',
                      border: `1px solid ${category.color}30`,
                    }}>
                      <div style={{ textAlign: 'center' }}>
                        <div style={{
                          fontSize: isMobile ? '0.9rem' : '1rem',
                          fontWeight: 'bold',
                          color: category.color,
                          marginBottom: '2px',
                        }}>
                          {category.stats.patients}
                        </div>
                        <div style={{
                          fontSize: isMobile ? '0.7rem' : '0.75rem',
                          color: '#64748b',
                        }}>
                          {category.stats.label1}
                        </div>
                      </div>
                      <div style={{ textAlign: 'center' }}>
                        <div style={{
                          fontSize: isMobile ? '0.9rem' : '1rem',
                          fontWeight: 'bold',
                          color: category.color,
                          marginBottom: '2px',
                        }}>
                          {category.stats.satisfaction}
                        </div>
                        <div style={{
                          fontSize: isMobile ? '0.7rem' : '0.75rem',
                          color: '#64748b',
                        }}>
                          {category.stats.label2}
                        </div>
                      </div>
                      <div style={{ textAlign: 'center' }}>
                        <div style={{
                          fontSize: isMobile ? '0.9rem' : '1rem',
                          fontWeight: 'bold',
                          color: category.color,
                          marginBottom: '2px',
                        }}>
                          {category.stats.experience}
                        </div>
                        <div style={{
                          fontSize: isMobile ? '0.7rem' : '0.75rem',
                          color: '#64748b',
                        }}>
                          {category.stats.label3}
                        </div>
                      </div>
                    </div>

                    {/* SUBCATEGORIES */}
                    {category.subcategories && (
                      <div style={{
                        display: 'flex',
                        flexDirection: 'column',
                        gap: '12px',
                      }}>
                        {category.subcategories.map((subcat, subIdx) => (
                          <div key={subIdx} style={{
                            border: `2px solid ${category.color}20`,
                            borderRadius: '12px',
                            overflow: 'hidden',
                            background: '#f8fafc',
                          }}>
                            <div style={{
                              minHeight: isMobile ? '70px' : '80px',
                              backgroundImage: `url(${subcat.image})`,
                              backgroundSize: 'cover',
                              backgroundPosition: 'center',
                              position: 'relative',
                            }}>
                              <div style={{
                                position: 'absolute',
                                bottom: 0,
                                left: 0,
                                right: 0,
                                background: `linear-gradient(to top, ${category.color}e6, transparent)`,
                                padding: '8px 12px',
                                display: 'flex',
                                alignItems: 'center',
                                gap: '8px',
                              }}>
                                <div style={{
                                  width: '32px',
                                  height: '32px',
                                  background: 'white',
                                  borderRadius: '6px',
                                  display: 'flex',
                                  alignItems: 'center',
                                  justifyContent: 'center',
                                  flexShrink: 0,
                                }}>
                                  <Heart size={16} color={category.color} />
                                </div>
                                <h5 style={{
                                  fontSize: isMobile ? '0.9rem' : '1rem',
                                  fontWeight: 'bold',
                                  color: 'white',
                                  margin: 0,
                                }}>
                                  {subcat.title}
                                </h5>
                              </div>
                            </div>

                            <div style={{ padding: isMobile ? '12px' : '16px' }}>
                              <ul style={{
                                listStyle: 'none',
                                padding: 0,
                                margin: 0,
                              }}>
                                {subcat.services.map((service, sIdx) => (
                                  <li key={sIdx} style={{
                                    marginBottom: isMobile ? '8px' : '12px',
                                    paddingBottom: isMobile ? '8px' : '12px',
                                    borderBottom: sIdx !== subcat.services.length - 1 ? '1px solid #e2e8f0' : 'none',
                                  }}>
                                    <div style={{
                                      display: 'flex',
                                      alignItems: 'flex-start',
                                      gap: '8px',
                                    }}>
                                      <span style={{
                                        color: category.color,
                                        fontWeight: 'bold',
                                        fontSize: '0.9rem',
                                        flexShrink: 0,
                                        marginTop: '2px',
                                      }}>✓</span>
                                      <div>
                                        <div style={{
                                          fontWeight: '600',
                                          color: '#0f172a',
                                          fontSize: isMobile ? '0.85rem' : '0.9rem',
                                          marginBottom: '2px',
                                        }}>
                                          {service.name}
                                        </div>
                                        <div style={{
                                          fontSize: isMobile ? '0.75rem' : '0.8rem',
                                          color: '#64748b',
                                          lineHeight: '1.3',
                                        }}>
                                          {service.desc}
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                ))}
                              </ul>
                            </div>
                          </div>
                        ))}
                      </div>
                    )}

                    {/* SERVICES LIST */}
                    {category.services && !category.subcategories && (
                      <div style={{
                        display: 'grid',
                        gridTemplateColumns: isMobile ? '1fr' : 'repeat(auto-fit, minmax(200px, 1fr))',
                        gap: '12px',
                      }}>
                        {category.services.map((service, idx) => (
                          <div key={idx} style={{
                            paddingBottom: '12px',
                            borderBottom: '1px solid #f1f5f9',
                          }}>
                            <div style={{
                              display: 'flex',
                              alignItems: 'flex-start',
                              gap: '8px',
                            }}>
                              <span style={{
                                color: category.color,
                                fontWeight: 'bold',
                                fontSize: '1rem',
                                flexShrink: 0,
                                marginTop: '2px',
                              }}>✓</span>
                              <div>
                                <div style={{
                                  fontWeight: '600',
                                  color: '#0f172a',
                                  fontSize: isMobile ? '0.85rem' : '0.9rem',
                                  marginBottom: '4px',
                                }}>
                                  {service.name}
                                </div>
                                <div style={{
                                  fontSize: isMobile ? '0.75rem' : '0.8rem',
                                  color: '#64748b',
                                  lineHeight: '1.4',
                                }}>
                                  {service.desc}
                                </div>
                              </div>
                            </div>
                          </div>
                        ))}
                      </div>
                    )}
                  </div>
                </div>
              )
            })}
          </div>

          {/* CTA BUTTON */}
          <div style={{
            textAlign: 'center',
            marginTop: isMobile ? '20px' : '28px',
          }}>
            <button style={{
              background: 'linear-gradient(135deg, #22c55e, #10b981)',
              color: 'white',
              padding: isMobile ? '12px 24px' : '14px 32px',
              borderRadius: '50px',
              border: 'none',
              fontSize: isMobile ? '0.9rem' : '1rem',
              fontWeight: 'bold',
              cursor: 'pointer',
              boxShadow: '0 6px 20px rgba(34, 197, 94, 0.4)',
              transition: 'all 0.3s ease',
              display: 'inline-flex',
              alignItems: 'center',
              gap: '6px'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'translateY(-2px)'
              e.currentTarget.style.boxShadow = '0 8px 30px rgba(34, 197, 94, 0.5)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'translateY(0)'
              e.currentTarget.style.boxShadow = '0 6px 20px rgba(34, 197, 94, 0.4)'
            }}>
              Prendre Rendez-vous
              <ChevronRight size={18} />
            </button>
          </div>
        </div>

        {/* OTHER SERVICES - LES 4 NOUVEAUX SERVICES */}
        <div style={{
          textAlign: 'center',
          margin: isMobile ? '20px 0 16px' : '32px 0 20px',
          opacity: isMobile ? 1 : (isVisible ? 1 : 0),
          transition: isMobile ? 'none' : 'opacity 0.6s ease-out 1s',
        }}>
          <h3 style={{
            fontSize: isMobile ? '1.3rem' : '1.75rem',
            fontWeight: 'bold',
            color: '#0f172a',
            margin: '0 0 8px 0'
          }}>
            Nos Autres Services
          </h3>
          <p style={{
            fontSize: isMobile ? '0.85rem' : '1rem',
            color: '#64748b',
            margin: 0
          }}>
            Une gamme complète pour tous vos besoins
          </p>
        </div>

        <div style={{
          display: 'grid',
          gridTemplateColumns: isMobile ? '1fr' : 'repeat(auto-fit, minmax(280px, 1fr))',
          gap: isMobile ? '12px' : '16px',
          marginBottom: isMobile ? '24px' : '32px',
        }}>
          {otherServices.map((service, index) => {
            const Icon = service.icon
            return (
              <div
                key={service.id}
                style={{
                  padding: isMobile ? '16px' : '20px',
                  background: 'rgba(255, 255, 255, 0.95)',
                  backdropFilter: 'blur(10px)',
                  border: `2px solid ${service.color}20`,
                  borderRadius: '12px',
                  transition: 'all 0.3s ease',
                  opacity: isMobile ? 1 : (isVisible ? 1 : 0),
                  transform: isMobile ? 'translateY(0)' : (isVisible ? 'translateY(0)' : 'translateY(50px)'),
                  transitionDelay: isMobile ? '0s' : `${1.2 + index * 0.1}s`,
                  cursor: 'pointer',
                }}
                onMouseEnter={(e) => {
                  setHoveredService(index)
                  e.currentTarget.style.transform = 'translateY(-10px)'
                  e.currentTarget.style.boxShadow = `0 20px 50px ${service.color}30`
                }}
                onMouseLeave={(e) => {
                  setHoveredService(null)
                  e.currentTarget.style.transform = 'translateY(0)'
                  e.currentTarget.style.boxShadow = 'none'
                }}
              >
                <div style={{
                  width: isMobile ? '48px' : '60px',
                  height: isMobile ? '48px' : '60px',
                  background: `linear-gradient(135deg, ${service.color}, ${service.color}dd)`,
                  borderRadius: '10px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: 'white',
                  marginBottom: '12px',
                  transition: 'transform 0.3s ease',
                  transform: hoveredService === index ? 'scale(1.1) rotate(5deg)' : 'scale(1)',
                }}>
                  <Icon size={isMobile ? 24 : 28} />
                </div>

                <h3 style={{
                  fontSize: isMobile ? '1rem' : '1.1rem',
                  fontWeight: 'bold',
                  marginBottom: '8px',
                  color: hoveredService === index ? service.color : '#0f172a',
                  transition: 'color 0.3s ease',
                  margin: '0 0 8px 0'
                }}>
                  {service.title}
                </h3>

                <p style={{
                  fontSize: isMobile ? '0.8rem' : '0.9rem',
                  color: '#64748b',
                  lineHeight: '1.5',
                  margin: 0,
                  marginBottom: '12px'
                }}>
                  {service.description}
                </p>

                <div style={{
                  color: service.color,
                  fontWeight: '600',
                  fontSize: isMobile ? '0.8rem' : '0.9rem',
                  transition: 'transform 0.3s ease',
                  transform: hoveredService === index ? 'translateX(5px)' : 'translateX(0)',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '4px'
                }}>
                  En savoir plus
                  <ChevronRight size={16} />
                </div>
              </div>
            )
          })}
        </div>

        {/* FEATURES */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: isMobile ? '1fr 1fr' : '1fr 1fr 1fr 1fr',
          gap: isMobile ? '8px' : '12px',
          padding: isMobile ? '12px' : '16px',
          background: 'linear-gradient(135deg, rgba(34, 197, 94, 0.08) 0%, rgba(16, 117, 185, 0.08) 100%)',
          backdropFilter: 'blur(10px)',
          borderRadius: '12px',
          border: '2px solid rgba(34, 197, 94, 0.15)'
        }}>
          {features.map((feature, index) => {
            const Icon = feature.icon
            return (
              <div key={index} style={{
                display: 'flex',
                alignItems: 'center',
                gap: '8px'
              }}>
                <div style={{
                  width: isMobile ? '32px' : '36px',
                  height: isMobile ? '32px' : '36px',
                  background: `linear-gradient(135deg, ${feature.color}, ${feature.color}cc)`,
                  borderRadius: '6px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: 'white',
                  boxShadow: `0 3px 10px ${feature.color}30`,
                  flexShrink: 0
                }}>
                  <Icon size={isMobile ? 16 : 18} />
                </div>
                <span style={{
                  fontWeight: '600',
                  color: '#0f172a',
                  fontSize: isMobile ? '0.7rem' : '0.8rem',
                  lineHeight: '1.2'
                }}>
                  {feature.text}
                </span>
              </div>
            )
          })}
        </div>
      </div>

      <style>
        {`
        @keyframes float {
          0%, 100% { transform: translateY(0px); }
          50% { transform: translateY(20px); }
        }

        @keyframes blink {
          0%, 50% { opacity: 1; }
          51%, 100% { opacity: 0; }
        }

        @media (max-width: 768px) {
          body { font-size: 14px; }
        }
        `}</style>
    </div>
  )
}

export default ServicesSection