// src/components/home/BlogSection.tsx
import { useState, useEffect, useRef } from 'react'
import { Calendar, User, ArrowRight, ChevronDown } from 'lucide-react'

const BlogSection = () => {
  const [hoveredArticle, setHoveredArticle] = useState<number | null>(null)
  const [selectedCategory, setSelectedCategory] = useState<string>('Tous')
  const [isDropdownOpen, setIsDropdownOpen] = useState(false)
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

  const categories = ['Tous', 'Santé', 'Urgences', 'Conseils', 'News']

  const articles = [
    {
      id: 1,
      title: 'L\'importance de l\'urgence médicale 24/7',
      category: 'Urgences',
      excerpt: 'Découvrez pourquoi avoir accès à des services d\'urgence 24/7 est crucial pour votre santé et celle de votre famille.',
      date: '15 Nov 2024',
      author: 'Dr. Moustapha',
      image: '/assets/medi.jpg',
      color: '#22c55e'
    },
    {
      id: 2,
      title: 'Transport médicalisé : ce qu\'il faut savoir',
      category: 'Conseils',
      excerpt: 'Guide complet sur le transport médicalisé, ses avantages et comment l\'utiliser en cas de besoin.',
      date: '12 Nov 2024',
      author: 'Équipe CMO',
      image: '/assets/49990.jpg',
      color: '#1075B9'
    },
    {
      id: 3,
      title: 'Prévention et bien-être : nos conseils',
      category: 'Santé',
      excerpt: 'Conseils pratiques pour maintenir une bonne santé et prévenir les maladies courantes.',
      date: '08 Nov 2024',
      author: 'Dr. Smith',
      image: '/assets/ethnic-doctor-working-with-patient.jpg',
      color: '#22c55e'
    },
    {
      id: 4,
      title: 'CMO VISTAMD inaugure un nouveau service',
      category: 'News',
      excerpt: 'Nouvelle section d\'assistance médicale pour renforcer notre engagement envers nos patients.',
      date: '05 Nov 2024',
      author: 'Communication',
      image: '/assets/person-their-job-position.jpg',
      color: '#1075B9'
    },
    {
      id: 5,
      title: 'Gestion des équipements médicaux modernes',
      category: 'Conseils',
      excerpt: 'Tout ce que les professionnels doivent savoir sur la maintenance des équipements médicaux.',
      date: '02 Nov 2024',
      author: 'Équipe Technique',
      image: '/assets/medical.jpg',
      color: '#22c55e'
    },
    {
      id: 6,
      title: 'Formations médicales : nouvelles sessions',
      category: 'News',
      excerpt: 'Inscrivez-vous à nos nouvelles formations en gestion de matériel et dispositifs médicaux.',
      date: '30 Oct 2024',
      author: 'Département Formation',
      image: '/assets/formation.png',
      color: '#1075B9'
    }
  ]

  const filteredArticles = selectedCategory === 'Tous' 
    ? articles 
    : articles.filter(article => article.category === selectedCategory)

  return (
    <div 
      ref={sectionRef}
      id='blog' 
      className="rts-blog-area rts-section-gap" 
      style={{
        background: 'linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 50%, #f8fafc 100%)',
        padding: '100px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      {/* Éléments de décoration animés avec fond coloré */}
      <div style={{
        position: 'absolute',
        top: '10%',
        left: '-8%',
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
        right: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.15) 0%, rgba(16, 117, 185, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 2s'
      }}></div>

      {/* SVG Newspaper - Top Left */}
      <svg 
        style={{
          position: 'absolute',
          top: '8%',
          left: '5%',
          width: '150px',
          height: '150px',
          opacity: 0.4,
          animation: 'float 9s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 12px rgba(34, 197, 94, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="20" y="20" width="60" height="65" rx="3" fill="#22c55e"/>
        <rect x="25" y="25" width="50" height="8" fill="white" rx="1"/>
        <rect x="25" y="38" width="25" height="25" fill="rgba(255,255,255,0.9)" rx="1"/>
        <line x1="55" y1="40" x2="70" y2="40" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="55" y1="47" x2="70" y2="47" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="55" y1="54" x2="70" y2="54" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="55" y1="61" x2="68" y2="61" stroke="white" strokeWidth="1.5" strokeLinecap="round"/>
        <line x1="25" y1="68" x2="70" y2="68" stroke="white" strokeWidth="1.5" strokeLinecap="round"/>
        <line x1="25" y1="74" x2="70" y2="74" stroke="white" strokeWidth="1.5" strokeLinecap="round"/>
        <line x1="25" y1="80" x2="60" y2="80" stroke="white" strokeWidth="1.5" strokeLinecap="round"/>
      </svg>

      {/* SVG Pen/Writing - Top Right */}
      <svg 
        style={{
          position: 'absolute',
          top: '10%',
          right: '7%',
          width: '130px',
          height: '130px',
          opacity: 0.4,
          animation: 'float 10s ease-in-out infinite 1s',
          filter: 'drop-shadow(0 6px 14px rgba(16, 117, 185, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <path d="M30,70 L40,30 L60,30 L70,70 Z" fill="#1075B9"/>
        <ellipse cx="50" cy="28" rx="10" ry="3" fill="#0ea5e9"/>
        <rect x="45" y="70" width="10" height="8" fill="#0c4a6e" rx="1"/>
        <path d="M45,78 L42,85 L58,85 L55,78 Z" fill="#0c4a6e"/>
        <line x1="40" y1="40" x2="60" y2="40" stroke="#0ea5e9" strokeWidth="2"/>
        <line x1="42" y1="50" x2="58" y2="50" stroke="#0ea5e9" strokeWidth="2"/>
        <line x1="44" y1="60" x2="56" y2="60" stroke="#0ea5e9" strokeWidth="2"/>
      </svg>

      {/* SVG Calendar - Bottom Right */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '12%',
          right: '8%',
          width: '140px',
          height: '140px',
          opacity: 0.35,
          animation: 'float 11s ease-in-out infinite 2s',
          filter: 'drop-shadow(0 6px 14px rgba(251, 191, 36, 0.3))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="20" y="25" width="60" height="60" rx="4" fill="white" stroke="#fbbf24" strokeWidth="3"/>
        <rect x="20" y="25" width="60" height="15" rx="4" fill="#fbbf24"/>
        <line x1="35" y1="20" x2="35" y2="30" stroke="#f59e0b" strokeWidth="3" strokeLinecap="round"/>
        <line x1="50" y1="20" x2="50" y2="30" stroke="#f59e0b" strokeWidth="3" strokeLinecap="round"/>
        <line x1="65" y1="20" x2="65" y2="30" stroke="#f59e0b" strokeWidth="3" strokeLinecap="round"/>
        <circle cx="32" cy="52" r="3" fill="#22c55e"/>
        <circle cx="45" cy="52" r="3" fill="#64748b"/>
        <circle cx="58" cy="52" r="3" fill="#64748b"/>
        <circle cx="68" cy="52" r="3" fill="#64748b"/>
        <circle cx="32" cy="63" r="3" fill="#64748b"/>
        <circle cx="45" cy="63" r="3" fill="#64748b"/>
        <circle cx="58" cy="63" r="3" fill="#64748b"/>
        <circle cx="68" cy="63" r="3" fill="#64748b"/>
        <circle cx="32" cy="74" r="3" fill="#64748b"/>
        <circle cx="45" cy="74" r="3" fill="#64748b"/>
      </svg>

      {/* SVG Notepad - Bottom Left */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '15%',
          left: '6%',
          width: '120px',
          height: '120px',
          opacity: 0.4,
          animation: 'float 8s ease-in-out infinite 3s',
          filter: 'drop-shadow(0 6px 14px rgba(34, 197, 94, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="30" y="20" width="40" height="60" rx="2" fill="#22c55e"/>
        <rect x="30" y="15" width="40" height="8" rx="3" fill="#10b981"/>
        <circle cx="45" cy="19" r="2" fill="white"/>
        <circle cx="55" cy="19" r="2" fill="white"/>
        <line x1="38" y1="30" x2="62" y2="30" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="38" y1="40" x2="62" y2="40" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="38" y1="50" x2="58" y2="50" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <line x1="38" y1="60" x2="62" y2="60" stroke="white" strokeWidth="1.5" strokeLinecap="round"/>
        <line x1="38" y1="68" x2="55" y2="68" stroke="white" strokeWidth="1.5" strokeLinecap="round"/>
      </svg>

      {/* SVG Bookmark - Middle Right */}
      <svg 
        style={{
          position: 'absolute',
          top: '40%',
          right: '3%',
          width: '90px',
          height: '90px',
          opacity: 0.3,
          animation: 'float 12s ease-in-out infinite 4s',
          filter: 'drop-shadow(0 4px 10px rgba(16, 117, 185, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <path d="M35,20 L65,20 L65,80 L50,70 L35,80 Z" fill="#1075B9" stroke="#0ea5e9" strokeWidth="2"/>
        <circle cx="50" cy="40" r="8" fill="#0ea5e9"/>
        <path d="M50,35 L50,45 M45,40 L55,40" stroke="white" strokeWidth="2" strokeLinecap="round"/>
      </svg>

      {/* SVG RSS Feed - Top Center */}
      <svg 
        style={{
          position: 'absolute',
          top: '5%',
          left: '50%',
          transform: 'translateX(-50%)',
          width: '100px',
          height: '100px',
          opacity: 0.35,
          animation: 'pulse 4s ease-in-out infinite',
          filter: 'drop-shadow(0 4px 8px rgba(239, 68, 68, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="50" r="40" fill="none" stroke="#ef4444" strokeWidth="3"/>
        <path d="M30,70 Q30,45 50,30 Q70,45 70,70" fill="none" stroke="#ef4444" strokeWidth="3" strokeLinecap="round"/>
        <circle cx="30" cy="70" r="5" fill="#ef4444"/>
        <path d="M35,60 Q35,50 50,42 Q65,50 65,60" fill="none" stroke="#ef4444" strokeWidth="2.5" strokeLinecap="round"/>
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
          margin: '0 auto 3rem'
        }}>
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.5rem',
            background: 'rgba(34, 197, 94, 0.15)',
            padding: '0.6rem 1.2rem',
            borderRadius: '2rem',
            marginBottom: '1rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'translateY(0)' : 'translateY(-30px)',
            transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s',
            boxShadow: '0 4px 12px rgba(34, 197, 94, 0.2)'
          }}>
            <span style={{
              color: '#22c55e',
              fontWeight: '700',
              fontSize: '0.9rem',
              textTransform: 'uppercase',
              letterSpacing: '0.1em'
            }}>
              Actualités & Blog
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
            Articles et Conseils Médicaux
          </h2>

          <p style={{
            fontSize: '1.25rem',
            color: '#64748b',
            lineHeight: '1.8',
            opacity: isVisible ? 1 : 0,
            transition: 'opacity 0.6s ease-out 0.4s'
          }}>
            Restez informé avec nos derniers articles sur la santé, les conseils médicaux et les actualités de CMO VISTAMD
          </p>
        </div>

        {/* Filtres de catégories - Dropdown */}
        <div style={{
          display: 'flex',
          justifyContent: 'center',
          marginBottom: '3rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
          transition: 'opacity 0.6s ease-out 0.5s, transform 0.6s ease-out 0.5s'
        }}>
          <div style={{ position: 'relative', minWidth: '200px' }}>
            <button
              onClick={() => setIsDropdownOpen(!isDropdownOpen)}
              style={{
                width: '100%',
                padding: '0.75rem 1.5rem',
                background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                color: 'white',
                fontWeight: '600',
                border: 'none',
                borderRadius: '0.75rem',
                cursor: 'pointer',
                transition: 'all 0.3s ease',
                fontSize: '0.95rem',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                gap: '0.5rem',
                boxShadow: '0 10px 25px rgba(34, 197, 94, 0.3)'
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.transform = 'translateY(-2px)'
                e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.4)'
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.transform = 'translateY(0)'
                e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.3)'
              }}
            >
              {selectedCategory}
              <ChevronDown 
                size={18} 
                style={{
                  transition: 'transform 0.3s ease',
                  transform: isDropdownOpen ? 'rotate(180deg)' : 'rotate(0)'
                }}
              />
            </button>

            {/* Dropdown Menu */}
            {isDropdownOpen && (
              <div style={{
                position: 'absolute',
                top: '100%',
                left: 0,
                right: 0,
                background: 'white',
                border: '2px solid rgba(34, 197, 94, 0.2)',
                borderRadius: '0.75rem',
                marginTop: '0.5rem',
                boxShadow: '0 15px 40px rgba(0, 0, 0, 0.12)',
                zIndex: 1000,
                overflow: 'hidden',
                animation: 'slideInUp 0.3s ease-out'
              }}>
                {categories.map((category, index) => (
                  <button
                    key={index}
                    onClick={() => {
                      setSelectedCategory(category)
                      setIsDropdownOpen(false)
                    }}
                    style={{
                      width: '100%',
                      padding: '0.75rem 1.5rem',
                      background: selectedCategory === category 
                        ? 'rgba(34, 197, 94, 0.15)' 
                        : 'transparent',
                      color: selectedCategory === category ? '#22c55e' : '#0f172a',
                      fontWeight: selectedCategory === category ? '600' : '500',
                      border: 'none',
                      borderBottom: index < categories.length - 1 ? '1px solid rgba(34, 197, 94, 0.1)' : 'none',
                      cursor: 'pointer',
                      transition: 'all 0.2s ease',
                      fontSize: '0.95rem',
                      textAlign: 'left'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.background = 'rgba(34, 197, 94, 0.08)'
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.background = selectedCategory === category 
                        ? 'rgba(34, 197, 94, 0.15)' 
                        : 'transparent'
                    }}
                  >
                    {category}
                  </button>
                ))}
              </div>
            )}
          </div>
        </div>

        {/* Articles Grid */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(350px, 1fr))',
          gap: '2rem',
          marginBottom: '3rem'
        }}>
          {filteredArticles.map((article, index) => (
            <div
              key={article.id}
              style={{
                background: '#ffffff',
                border: '2px solid rgba(34, 197, 94, 0.15)',
                borderRadius: '1rem',
                overflow: 'hidden',
                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                opacity: isVisible ? 1 : 0,
                transform: isVisible ? 'translateY(0) scale(1)' : 'translateY(50px) scale(0.95)',
                transitionDelay: `${0.6 + index * 0.1}s`,
                cursor: 'pointer',
                boxShadow: '0 8px 20px rgba(0, 0, 0, 0.08)'
              }}
              onMouseEnter={(e) => {
                setHoveredArticle(index)
                e.currentTarget.style.transform = 'translateY(-10px) scale(1.02)'
                e.currentTarget.style.boxShadow = `0 20px 50px ${article.color}25`
                e.currentTarget.style.borderColor = article.color
              }}
              onMouseLeave={(e) => {
                setHoveredArticle(null)
                e.currentTarget.style.transform = 'translateY(0) scale(1)'
                e.currentTarget.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.08)'
                e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.15)'
              }}
            >
              {/* Image Header */}
              <div style={{
                height: '220px',
                position: 'relative',
                overflow: 'hidden'
              }}>
                <div style={{
                  height: '100%',
                  background: `url(${article.image})`,
                  backgroundSize: 'cover',
                  backgroundPosition: 'center',
                  transition: 'all 0.5s ease',
                  transform: hoveredArticle === index ? 'scale(1.15)' : 'scale(1)'
                }}>
                  {/* Fallback si image ne charge pas */}
                  <div style={{
                    width: '100%',
                    height: '100%',
                    background: `linear-gradient(135deg, ${article.color}, ${article.color}dd)`,
                    display: article.image.includes('http') || article.image.includes('/') ? 'none' : 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    fontSize: '80px'
                  }}>
                    📄
                  </div>
                </div>

                {/* Overlay gradient au survol */}
                <div style={{
                  position: 'absolute',
                  top: 0,
                  left: 0,
                  right: 0,
                  bottom: 0,
                  background: `linear-gradient(135deg, ${article.color}50, transparent)`,
                  opacity: hoveredArticle === index ? 1 : 0,
                  transition: 'opacity 0.3s ease'
                }}></div>
              </div>

              {/* Content */}
              <div style={{
                padding: '1.75rem',
                position: 'relative'
              }}>
                {/* Category Badge */}
                <div style={{
                  display: 'inline-block',
                  background: `${article.color}20`,
                  color: article.color,
                  padding: '0.5rem 1rem',
                  borderRadius: '0.5rem',
                  fontSize: '0.8rem',
                  fontWeight: '600',
                  marginBottom: '1rem',
                  textTransform: 'uppercase',
                  letterSpacing: '0.05em',
                  animation: hoveredArticle === index ? 'pulse 1s ease-in-out infinite' : 'none'
                }}>
                  {article.category}
                </div>

                {/* Title */}
                <h3 style={{
                  fontSize: '1.5rem',
                  fontWeight: 'bold',
                  marginBottom: '1rem',
                  lineHeight: '1.4',
                  transition: 'color 0.3s ease',
                  color: hoveredArticle === index ? article.color : '#0f172a'
                }}>
                  {article.title}
                </h3>

                {/* Excerpt */}
                <p style={{
                  fontSize: '1rem',
                  color: '#64748b',
                  lineHeight: '1.6',
                  marginBottom: '1.5rem'
                }}>
                  {article.excerpt}
                </p>

                {/* Meta Info */}
                <div style={{
                  display: 'flex',
                  flexWrap: 'wrap',
                  gap: '1.5rem',
                  paddingTop: '1rem',
                  borderTop: '1px solid rgba(34, 197, 94, 0.15)',
                  marginBottom: '1.5rem',
                  fontSize: '0.9rem',
                  color: '#64748b'
                }}>
                  <div style={{ 
                    display: 'flex', 
                    alignItems: 'center', 
                    gap: '0.5rem',
                    animation: hoveredArticle === index ? 'iconBounce 0.6s ease' : 'none'
                  }}>
                    <Calendar size={16} style={{ color: article.color }} />
                    {article.date}
                  </div>
                  <div style={{ 
                    display: 'flex', 
                    alignItems: 'center', 
                    gap: '0.5rem',
                    animation: hoveredArticle === index ? 'iconBounce 0.6s ease 0.1s' : 'none'
                  }}>
                    <User size={16} style={{ color: article.color }} />
                    {article.author}
                  </div>
                </div>

                {/* Read More Button */}
                <button
                  style={{
                    width: '100%',
                    padding: '0.75rem',
                    background: hoveredArticle === index 
                      ? `linear-gradient(135deg, ${article.color}, ${article.color}dd)` 
                      : `${article.color}18`,
                    color: hoveredArticle === index ? 'white' : article.color,
                    fontWeight: '600',
                    border: 'none',
                    borderRadius: '0.5rem',
                    cursor: 'pointer',
                    transition: 'all 0.3s ease',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '0.5rem',
                    fontSize: '0.95rem',
                    position: 'relative',
                    overflow: 'hidden'
                  }}
                >
                  <span style={{ position: 'relative', zIndex: 1 }}>Lire la suite</span>
                  <ArrowRight 
                    size={16} 
                    style={{
                      position: 'relative',
                      zIndex: 1,
                      transition: 'transform 0.3s ease',
                      transform: hoveredArticle === index ? 'translateX(5px)' : 'translateX(0)',
                      animation: hoveredArticle === index ? 'arrowBounce 0.6s ease infinite' : 'none'
                    }} 
                  />
                  
                  {/* Effet shimmer au survol */}
                  <span style={{
                    position: 'absolute',
                    top: 0,
                    left: hoveredArticle === index ? '0' : '-100%',
                    width: '100%',
                    height: '100%',
                    background: 'linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent)',
                    transition: 'left 0.6s ease',
                    pointerEvents: 'none'
                  }}></span>
                </button>
              </div>
            </div>
          ))}
        </div>

        {/* Pagination ou CTA */}
        <div style={{
          textAlign: 'center',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
          transition: 'opacity 0.6s ease-out 1.2s, transform 0.6s ease-out 1.2s'
        }}>
          <p style={{
            fontSize: '1rem',
            color: '#64748b',
            marginBottom: '1.5rem'
          }}>
            Vous voulez voir plus d'articles ?
          </p>
          <button
            style={{
              padding: '1rem 2rem',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              color: 'white',
              fontWeight: 'bold',
              border: 'none',
              borderRadius: '0.75rem',
              fontSize: '0.95rem',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)',
              display: 'inline-flex',
              alignItems: 'center',
              gap: '0.5rem'
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
            Voir tous les articles
            <ArrowRight size={20} />
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
            transform: scale(1.05);
          }
        }

        @keyframes iconBounce {
          0%, 100% {
            transform: scale(1);
          }
          50% {
            transform: scale(1.2);
          }
        }

        @keyframes arrowBounce {
          0%, 100% {
            transform: translateX(5px);
          }
          50% {
            transform: translateX(10px);
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

export default BlogSection