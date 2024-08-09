        const cddInput = document.getElementById('cdd');
        const genreInput = document.getElementById('genre');

        cddInput.addEventListener('input', (e) => {
            const cdd = e.target.value.trim();
            const cddDoisDigitos = cdd.substring(0, 2); // pegar os dois primeiros dígitos
            const genre = getGenreFromCDD(cddDoisDigitos);
            genreInput.value = genre;
        });

        function getGenreFromCDD(cdd) {
            const cddToGenre = {
                '00': 'Generalidades',
                '01': 'Bibliografia',
                '02': 'Biblioteca e Informática',
                '03': 'Enciclopédias em Geral',
                '05': 'Publicações em Série',
                '06': 'Organizações e Museografia',
                '07': 'Mídia, Jornalismo e Publicação',
                '08': 'Coleções em Geral',
                '09': 'Manuscritos e Livros Raros',

                '10': 'Filosofia e Psicologia',
                '11': 'Metafísica',
                '12': 'Epistemologia, Causas, Fins, Gênero Humano',
                '13': 'Fenômenos Paranormais',
                '14': 'Escolas Filosóficas Específicas',
                '15': 'Psicologia',
                '16': 'Lógica',
                '17': 'Ética (Filosofia Moral)',
                '18': 'Filosofia Antiga, Medieval e Oriental',
                '19': 'Filosofia Moderna Ocidental',

                '20': 'Religião',
                '21': 'Teologia Natural',
                '22': 'Bíblia',
                '23': 'Teologia Cristã',
                '24': 'Prática Cristã',
                '25': 'Igreja e Ministérios',
                '26': 'Teologia Social Cristã',
                '27': 'História da Igreja Cristã',
                '28': 'Denominações Cristãs e Seitas',
                '29': 'Outras religiões',

                '30': 'Ciências Sociais',
                '31': 'Estatística Gerai',
                '32': 'Ciência Política',
                '33': 'Economia',
                '34': 'Direito',
                '35': 'Administração Pública e Ciência Militar',
                '36': 'Serviço social / Assistência social',
                '37': 'Educação',
                '38': 'Comércio, Comunicação e Transporte',
                '39': 'Costumes, Etiqueta, Folclore',

                '40': 'Línguas',
                '41': 'Linguística Geral',
                '42': 'Inglês e Inglês Antigo',
                '43': 'Línguas Germânicas - Alemão',
                '44': 'Língua Francesa - Romances',
                '45': 'Língua Italiana, Romana, Retrorromana',
                '46': 'Línguas Espanhola e Portuguesa',
                '47': 'Línguas Itálicas, Latinas',
                '48': 'Línguas Helênicas, Grego Clássico',
                '49': 'Outras Línguas',

                '50': 'Ciências Naturais e Matemáticas',
                '51': 'Matemática',
                '52': 'Astronomia e Ciências Afins',
                '53': 'Física',
                '54': 'Química',
                '55': 'Ciências da Terra',
                '56': 'Paleontologia, Paleozoologia',
                '57': 'Ciências da Vida (Biológicas)',
                '58': 'Botânica',
                '59': 'Zoologia',

                '60': 'Tecnologia - Ciências Aplicadas',
                '61': 'Medicina e Saúde',
                '62': 'Engenharia e Operações Afins',
                '63': 'Agricultura e Ciências Afins',
                '64': 'Economia Doméstica e Vida Familiar',
                '65': 'Administração e Serviços Auxiliares',
                '66': 'Engenharia Química',
                '67': 'Processos Industriais e Manufatura',
                '68': 'Manufatura para Fins Específicos',
                '69': 'Construção e Materiais de Construção',

                '70': 'Artes',
                '71': 'Urbanismo e Paisagismo',
                '72': 'Arquitetura',
                '73': 'Artes Plásticas - Escultura',
                '74': 'Desenho e Artes Decorativas',
                '75': 'Pintura e Pintura em Geral',
                '76': 'Artes Gráficas, Arte de Gravar e Gravados',
                '77': 'Fotografia e Artes Visuais',
                '78': 'Música',
                '79': 'Artes Recreativas e de Representar',

                '80': 'Literatura e Retórica',
                '81': 'Literatura dos Americana e Canadense em Inglês',
                '82': 'Literatura Inglesa',
                '83': 'Literatura Alemã',
                '84': 'Literatura Francesa',
                '85': 'Literatura Italiana, Romana, Retrorromana',
                '86': 'Literatura Espanhola e Portuguesa',
                '87': 'Literatura Latina',
                '88': 'Literatura Helênicas - Grega Clássica',
                '89': 'Literatura de Outras Línguas',

                '90': 'Geografia, História e Biografia',
                '91': 'Geografia e Viagens',
                '92': 'Biografias e Obras Relacionadas',
                '93': 'História do Mundo Antigo',
                '94': 'História da Europa',
                '95': 'História da Ásia e Extremo Oriente',
                '96': 'História da África',
                '97': 'História da América do Norte',
                '98': 'História da América do Sul',
                '99': 'História de Outras Regiões',

            };

            return cddToGenre[cdd] || '';
        }
   
