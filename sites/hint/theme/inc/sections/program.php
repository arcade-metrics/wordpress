<?php
/**
 * Section: Scientific Programme (#program).
 *
 * One day (Thursday 25 February 2027, Red Auditorium, EACH-USP) rendered as
 * a timeline per period (morning / afternoon). Round tables nest their talks;
 * each talk shows a speaker card (photo, name, role, talk title) with the
 * short bio inside <details>. Content sources: sites/hint/docs/programa-v1.md
 * and "Hint site (1).docx.md" ("Na aba: Programação científica"). Photos are
 * 320x320 JPEGs in assets/speakers/<slug>.jpg. Text in both languages goes
 * through hint_pick(); styles in assets/css/sections/program.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Event day used in <time datetime> attributes (local time, UTC-3).
 */
define( 'HINT_PROGRAM_DATE', '2027-02-25' );

/**
 * Speakers and moderators, keyed by slug (also the photo file name).
 *
 * Bios are arrays of paragraphs per language. Built once per request: the
 * speaker card renderer calls this for every card.
 *
 * @return array<string,array{name:string,bio:array{pt:string[],en:string[]}}>
 */
function hint_get_program_speakers() {
	static $speakers = null;

	if ( null !== $speakers ) {
		return $speakers;
	}

	$speakers = array(
		'carlos-bandeira-de-mello-monteiro'          => array(
			'name' => 'Carlos Bandeira de Mello Monteiro',
			'bio'  => array(
				'pt' => array(
					'Livre-docente pela Universidade de São Paulo (USP), é doutor em Ciências, na área de Neurologia, pela mesma instituição, mestre em Distúrbios do Desenvolvimento e graduado em Fisioterapia e Educação Física. Realizou pós-doutorado na Faculdade de Saúde Pública da USP, com experiências internacionais na Vrije Universiteit, na Holanda, e na Harvard Medical School, nos Estados Unidos. É professor do curso de Educação Física e Saúde da Escola de Artes, Ciências e Humanidades da USP (EACH-USP) e mantém parcerias de pesquisa com universidades do Reino Unido. Sua atuação concentra-se no desenvolvimento de plataformas virtuais, realidade virtual e Inteligência Artificial aplicadas à reabilitação.',
				),
				'en' => array(
					'Habilitation (livre-docência) from the University of São Paulo (USP), PhD in Sciences (Neurology) from the same institution, MSc in Developmental Disorders and degrees in Physiotherapy and Physical Education. He completed a postdoctoral fellowship at the USP School of Public Health, with international experience at Vrije Universiteit in the Netherlands and at Harvard Medical School in the United States. He is a professor in the Physical Education and Health programme at the USP School of Arts, Sciences and Humanities (EACH-USP) and maintains research partnerships with universities in the United Kingdom. His work focuses on the development of virtual platforms, virtual reality and Artificial Intelligence applied to rehabilitation.',
				),
			),
		),
		'eduardo-dati-dias'                          => array(
			'name' => 'Eduardo Dati Dias',
			'bio'  => array(
				'pt' => array(
					'Doutor em Ciências da Reabilitação pela Faculdade de Medicina da Universidade de São Paulo (FMUSP), com extensão doutoral na Universidade de Oxford, no Reino Unido, possui formação interdisciplinar nas áreas de Educação Física, Computação e Ciências da Saúde. Atualmente, realiza pós-doutorado na USP, com foco na aplicação da Inteligência Artificial ao reconhecimento de padrões na neurodivergência. É líder de tecnologia do CIATEC (Centro de Inclusão e Acessibilidade por meio da Tecnologia) e pesquisador do PATER-USP. Com mais de 25 anos de experiência em projetos de saúde e bem-estar, atua no desenvolvimento de tecnologias digitais, jogos sérios e biomarcadores digitais aplicados à reabilitação, à avaliação motora e à inclusão de pessoas com deficiência, com ênfase em Inteligência Artificial e inovação em saúde digital.',
				),
				'en' => array(
					'PhD in Rehabilitation Sciences from the University of São Paulo School of Medicine (FMUSP), with a doctoral research period at the University of Oxford, United Kingdom, and an interdisciplinary background in Physical Education, Computing and Health Sciences. He is currently a postdoctoral researcher at USP, focusing on Artificial Intelligence applied to pattern recognition in neurodivergence. He is the technology lead at CIATEC (Centre for Inclusion and Accessibility through Technology) and a researcher at PATER-USP. With more than 25 years of experience in health and well-being projects, he works on digital technologies, serious games and digital biomarkers applied to rehabilitation, motor assessment and the inclusion of people with disabilities, with an emphasis on Artificial Intelligence and digital health innovation.',
				),
			),
		),
		'ruth-caldeira-de-mello'                     => array(
			'name' => 'Ruth Caldeira de Mello',
			'bio'  => array(
				'pt' => array(
					'Professora Associada da Universidade de São Paulo (EACH-USP), graduada, mestre e doutora em Fisioterapia pela UFSCar, com pós-doutorado em Gerontologia pela UNICAMP. Docente do curso de Gerontologia e orientadora do Programa de Pós-Graduação em Gerontologia da EACH-USP. Possui experiência na área do envelhecimento, com pesquisas voltadas à atividade física, desempenho físico-funcional, avaliação multidimensional da pessoa idosa, quedas, fragilidade e cuidados de longa duração.',
				),
				'en' => array(
					'Associate Professor at the University of São Paulo (EACH-USP), with a bachelor\'s, master\'s and PhD in Physiotherapy from UFSCar and a postdoctoral fellowship in Gerontology at UNICAMP. She teaches in the Gerontology programme and supervises in the Graduate Programme in Gerontology at EACH-USP. Her experience lies in the field of ageing, with research on physical activity, physical and functional performance, multidimensional assessment of older adults, falls, frailty and long-term care.',
				),
			),
		),
		'isabela-barbieri'                           => array(
			'name' => 'Isabela Barbieri',
			'bio'  => array(
				'pt' => array(
					'Bolsista de Treinamento Técnico. Graduada em Letras Libras – Licenciatura pela UFSC e em Gestão de Recursos Humanos. Foi bolsista de pesquisa em tradução e interpretação na UFSC e realizou estágio na UNICAMP, atuando no ensino de português como segunda língua (L2) para crianças surdas.',
				),
				'en' => array(
					'Technical Training Fellow. She holds a teaching degree in Libras (Brazilian Sign Language) from UFSC and a degree in Human Resources Management. She was a research fellow in translation and interpreting at UFSC and completed an internship at UNICAMP, teaching Portuguese as a second language (L2) to deaf children.',
				),
			),
		),
		'talita-dias-da-silva-magalhaes'             => array(
			'name' => 'Talita Dias da Silva-Magalhães',
			'bio'  => array(
				'pt' => array(
					'Fisioterapeuta e Research Fellow da Universidade de Exeter, na Inglaterra, é doutora pela Universidade Federal de São Paulo (UNIFESP) e realizou pós-doutorados na Universidade de Oxford e na Universidade de São Paulo (USP). Atua como orientadora e professora na pós-graduação em Bioengenharia da Universidade Brasil e orienta pesquisas no Programa de Pós-Graduação em Medicina (Cardiologia) da UNIFESP. É pesquisadora do Grupo de Pesquisa e Aplicação Tecnológica em Reabilitação (PATER-USP) e possui experiência no desenvolvimento e na aplicação de tecnologias para a promoção da saúde e reabilitação. Suas principais áreas de atuação incluem realidade virtual, telerreabilitação, aprendizagem motora, neuromodulação e reabilitação de crianças e adolescentes com deficiência e diferentes condições de saúde.',
				),
				'en' => array(
					'Physiotherapist and Research Fellow at the University of Exeter, England, she holds a PhD from the Federal University of São Paulo (UNIFESP) and completed postdoctoral fellowships at the University of Oxford and the University of São Paulo (USP). She teaches and supervises in the graduate programme in Bioengineering at Universidade Brasil and supervises research in the Graduate Programme in Medicine (Cardiology) at UNIFESP. She is a researcher in the Research and Technological Application in Rehabilitation Group (PATER-USP) and has experience in developing and applying technologies for health promotion and rehabilitation. Her main areas of work include virtual reality, telerehabilitation, motor learning, neuromodulation and the rehabilitation of children and adolescents with disabilities and different health conditions.',
				),
			),
		),
		'eladio-sebastian-heredero'                  => array(
			'name' => 'Eladio Sebastián Heredero',
			'bio'  => array(
				'pt' => array(
					'Professor Doutor Aposentado da Universidade de Alcalá, na Espanha. Atualmente Professor Visitante Estrangeiro no PROFEI da UNIFESP, no Brasil, e professor colaborador dos programas de pós-graduação da UAH, na Espanha, da UFMS, no Brasil, e da UNAB, na Colômbia.',
					'Possui graduação em Magistério e em Pedagogia e doutorado em Educação pela Universidade de Alcalá, na Espanha. Pós-doutorado em Educação pela Universidade Estadual Paulista "Júlio de Mesquita Filho" (UNESP) e Cátedra Distinguida de Educação 2016 pela Universidade CETYS, no México.',
					'Atuou como assessor da área de atenção à diversidade do Ministério da Educação espanhol e da Secretaria de Educação de Castilla-La Mancha. Foi diretor do Centro de Formação Continuada de Professores e de Recursos do Ministério da Educação da Espanha e de Castilla-La Mancha. Atuou como orientador educacional e professor dos ensinos básico, médio e superior, consultor externo do Instituto Ayrton Senna e palestrante e formador em diversas universidades e secretarias de educação.',
					'Líder do Grupo de Estudos, Políticas e Pesquisas em Educação Inclusiva – Reconstruindo a Escola (GEPPEI-RE), com foco no Desenho Universal para Aprendizagem. Seus livros, capítulos e artigos sobre o tema destacam a importância de uma educação inclusiva de qualidade e para todos.',
				),
				'en' => array(
					'Retired Professor at the University of Alcalá, Spain. He is currently a Visiting Foreign Professor in the PROFEI programme at UNIFESP, Brazil, and a collaborating professor in the graduate programmes of UAH (Spain), UFMS (Brazil) and UNAB (Colombia).',
					'He holds degrees in Teaching and in Pedagogy and a PhD in Education from the University of Alcalá, Spain, a postdoctoral fellowship in Education from São Paulo State University (UNESP) and the 2016 Distinguished Chair in Education from CETYS University, Mexico.',
					'He served as an adviser on attention to diversity for the Spanish Ministry of Education and for the Department of Education of Castilla-La Mancha, and directed the Teacher Training and Resource Centre of the Spanish Ministry of Education and of Castilla-La Mancha. He has worked as an educational counsellor and as a teacher at primary, secondary and higher education levels, as an external consultant to the Ayrton Senna Institute and as a speaker and trainer at several universities and education departments.',
					'He leads the Study, Policy and Research Group on Inclusive Education – Rebuilding the School (GEPPEI-RE), focused on Universal Design for Learning. His books, chapters and articles on the subject highlight the importance of quality inclusive education for all.',
				),
			),
		),
		'eniceia-mendes'                             => array(
			'name' => 'Eniceia Mendes',
			'bio'  => array(
				'pt' => array(
					'Doutora em Psicologia pelo IP-USP (1995), mestre em Educação Especial pela UFSCar e graduada em Psicologia pela FCLRP-USP. Professora Titular da Universidade Federal de São Carlos, docente do Departamento de Psicologia e do Programa de Pós-Graduação em Educação Especial, coordenadora da rede de pesquisadores do Observatório Nacional de Educação Especial (ONEESP) e líder do Grupo de Pesquisa sobre Formação de Recursos Humanos em Educação Especial (GP-FOREESP). Coordenadora do Programa de Extensão sobre Formação Continuada em Educação Especial (PROEX/UFSCar). Realizou estágios no exterior (Estados Unidos, França, México e Peru) e pós-doutorados na Université Paris V – Sorbonne, na França (2007-2008), e na Universidade de Salamanca, na Espanha (2017). Atua na área de Educação, com ênfase em Educação Especial, e desenvolve e orienta pesquisas sobre inclusão escolar, formação e política educacional. Foi presidente da Associação Brasileira de Pesquisadores da Educação Especial (ABPEE, 2013-2016), presidiu a comissão organizadora do IV ao VIII Congresso Brasileiro de Educação Especial (CBEE) e integrou o Comitê Assessor do CNPq (2015-2016). Bolsista de Produtividade em Pesquisa 1B do CNPq.',
				),
				'en' => array(
					'PhD in Psychology from IP-USP (1995), MSc in Special Education from UFSCar and a degree in Psychology from FCLRP-USP. Full Professor at the Federal University of São Carlos, where she teaches in the Department of Psychology and in the Graduate Programme in Special Education; coordinator of the researchers\' network of the National Observatory of Special Education (ONEESP) and leader of the Research Group on Human Resources Training in Special Education (GP-FOREESP). She coordinates the outreach programme on continuing education in Special Education (PROEX/UFSCar). She has undertaken research stays abroad (United States, France, Mexico and Peru) and postdoctoral fellowships at Université Paris V – Sorbonne, France (2007-2008), and the University of Salamanca, Spain (2017). She works in Education with an emphasis on Special Education, conducting and supervising research on school inclusion, teacher training and educational policy. She was president of the Brazilian Association of Special Education Researchers (ABPEE, 2013-2016), chaired the organising committee of the 4th to 8th Brazilian Congress of Special Education (CBEE) and served on the CNPq Advisory Committee (2015-2016). CNPq Research Productivity Fellow (level 1B).',
				),
			),
		),
		'maraina-fernandes-de-aguiar'                => array(
			'name' => 'Maraína Fernandes de Aguiar',
			'bio'  => array(
				'pt' => array(
					'Advogada, professora e especialista em Coordenação Pedagógica (UFSCar) e em Inovação na Educação Mediada por Tecnologias (UFABC), com mais de 20 anos de experiência na rede pública. Atualmente atua na Diretoria de Educação Especial e Inclusão da Secretaria da Educação do Estado de São Paulo, contribuindo para a formulação e a implementação de políticas públicas de inclusão, equidade e diversidade. Possui experiência em docência, gestão escolar e formação, atuando também como palestrante na defesa de uma educação acessível, intercultural e comprometida com o direito de todos à aprendizagem.',
				),
				'en' => array(
					'Lawyer, teacher and specialist in Pedagogical Coordination (UFSCar) and in Technology-Mediated Educational Innovation (UFABC), with more than 20 years of experience in the public school system. She currently works in the Directorate of Special Education and Inclusion of the São Paulo State Department of Education, contributing to the design and implementation of public policies on inclusion, equity and diversity. She has experience in teaching, school management and teacher training, and speaks in defence of an accessible, intercultural education committed to everyone\'s right to learn.',
				),
			),
		),
		'mariana-moraes-lopes'                       => array(
			'name' => 'Mariana Moraes Lopes',
			'bio'  => array(
				'pt' => array(
					'Doutora e mestre em Educação Especial pela Universidade Federal de São Carlos (UFSCar), é especialista em Neuropsicologia e Educação Inclusiva, psicopedagoga e pedagoga pela Universidade Federal da Bahia (UFBA). Possui experiência na educação básica e no ensino superior, com atuação nas áreas de Educação Especial, Educação Inclusiva, Tecnologia Assistiva e formação de professores. Atualmente é pesquisadora bolsista da FAPESP no CIATEC (Centro de Inclusão e Acessibilidade por meio da Tecnologia), desenvolvendo atividades relacionadas à inclusão e à acessibilidade por meio da tecnologia.',
				),
				'en' => array(
					'PhD and MSc in Special Education from the Federal University of São Carlos (UFSCar), specialist in Neuropsychology and Inclusive Education, psychopedagogue and pedagogue trained at the Federal University of Bahia (UFBA). She has experience in basic and higher education, working in Special Education, Inclusive Education, Assistive Technology and teacher training. She is currently a FAPESP research fellow at CIATEC (Centre for Inclusion and Accessibility through Technology), working on inclusion and accessibility through technology.',
				),
			),
		),
		'jose-eduardo-santarem-segundo'              => array(
			'name' => 'José Eduardo Santarem Segundo',
			'bio'  => array(
				'pt' => array(
					'Livre-docente em Informação e Tecnologia pela Universidade de São Paulo (USP), é doutor e mestre em Ciência da Informação pela UNESP e realizou pós-doutorado na Western University, no Canadá. Atua como Professor Doutor na USP e docente do Programa de Pós-Graduação em Ciência da Informação da UNESP. É bolsista de Produtividade em Pesquisa do CNPq e desenvolve pesquisas nas áreas de Inteligência Artificial, Aprendizado de Máquina, Web Semântica, Big Data e tecnologias digitais. Lidera o Núcleo de Estudos em Web Semântica e Análise de Dados (NEWSDA) e possui reconhecimentos nacionais por sua produção científica, incluindo o Prêmio de Melhor Tese da ANCIB e menção honrosa no Prêmio CAPES de Teses, ambos em 2011.',
				),
				'en' => array(
					'Habilitation (livre-docência) in Information and Technology from the University of São Paulo (USP), PhD and MSc in Information Science from UNESP and a postdoctoral fellowship at Western University, Canada. He is a professor at USP and teaches in the Graduate Programme in Information Science at UNESP. A CNPq Research Productivity Fellow, he conducts research in Artificial Intelligence, Machine Learning, the Semantic Web, Big Data and digital technologies. He leads the Semantic Web and Data Analysis Research Centre (NEWSDA) and has received national recognition for his scientific output, including the ANCIB Best Thesis Award and an honourable mention in the CAPES Thesis Award, both in 2011.',
				),
			),
		),
		'fatima-de-lourdes-dos-santos-nunes-marques' => array(
			'name' => 'Fátima de Lourdes dos Santos Nunes Marques',
			'bio'  => array(
				'pt' => array(
					'Professora Titular da Universidade de São Paulo. Bacharel em Ciência da Computação pela Universidade Estadual Paulista "Júlio de Mesquita Filho", mestre em Engenharia Elétrica e doutora em Ciências (Física Computacional) pela Universidade de São Paulo. Livre-docente pela Universidade de São Paulo na área de Processamento Gráfico. Suas pesquisas concentram-se predominantemente na área de Computação, com ênfase em Realidade Virtual, Processamento de Imagens e recuperação de dados multimídia por conteúdo. Vários de seus trabalhos são aplicados à área da saúde, estabelecendo interfaces com a Engenharia Biomédica.',
				),
				'en' => array(
					'Full Professor at the University of São Paulo. She holds a BSc in Computer Science from São Paulo State University (UNESP), an MSc in Electrical Engineering and a PhD in Sciences (Computational Physics) from the University of São Paulo, and a habilitation (livre-docência) from USP in Graphics Processing. Her research lies predominantly in Computing, with an emphasis on Virtual Reality, Image Processing and content-based multimedia retrieval. Much of her work is applied to healthcare, at the interface with Biomedical Engineering.',
				),
			),
		),
		'helen-dawes'                                => array(
			'name' => 'Helen Dawes',
			'bio'  => array(
				'pt' => array(
					'Professora de Reabilitação Clínica na Universidade de Exeter, no Reino Unido, é fisioterapeuta e pesquisadora com trajetória acadêmica voltada à reabilitação e à promoção da saúde. Atua como uma das líderes da área de Reabilitação do NIHR Exeter Biomedical Research Centre, desenvolvendo pesquisas que integram inovação tecnológica, prática clínica e necessidades dos pacientes. Sua atuação concentra-se no desenvolvimento, na avaliação e na implementação de soluções inovadoras e personalizadas para a reabilitação de pessoas com condições que afetam o movimento, buscando ampliar a autonomia, a funcionalidade e a qualidade de vida. Possui experiência em colaborações internacionais e no desenvolvimento de tecnologias aplicadas à saúde e à reabilitação.',
				),
				'en' => array(
					'Professor of Clinical Rehabilitation at the University of Exeter, United Kingdom, she is a physiotherapist and researcher whose academic career is devoted to rehabilitation and health promotion. She is one of the leads of the Rehabilitation theme at the NIHR Exeter Biomedical Research Centre, developing research that brings together technological innovation, clinical practice and patients\' needs. Her work focuses on developing, evaluating and implementing innovative, personalised solutions for the rehabilitation of people with conditions that affect movement, aiming to increase autonomy, function and quality of life. She has extensive experience in international collaborations and in developing technologies applied to health and rehabilitation.',
				),
			),
		),
		'andrea-nemeth'                              => array(
			'name' => 'Andrea Németh',
			'bio'  => array(
				'pt' => array(
					'Professora e médica consultora em Neurogenética, é cientista clínica na Universidade de Oxford e atua no Oxford University Hospitals NHS Trust, no Reino Unido. Formou-se na Royal Free Hospital School of Medicine, com bacharelado intercalado em Bioquímica e Neurofarmacologia (Royal Free e University College London), e concluiu o doutorado (DPhil) na Universidade de Oxford em 1995, no laboratório da professora Kay Davies, como MRC Clinical Training Fellow. Em seguida recebeu uma MRC Clinician Scientist Fellowship, desenvolvida no Wellcome Trust Centre for Human Genetics, em Oxford, período em que se dedicou às ataxias e a outros distúrbios do movimento e caracterizou diversas síndromes genéticas de ataxia até então desconhecidas. Concluiu a formação clínica em Genética como Clinical Lecturer na Universidade de Oxford, antes de se tornar consultora em Neurogenética e integrar o Nuffield Department of Clinical Neurosciences (NDCN). Além do MRC, sua pesquisa foi financiada por Ataxia UK, Action Medical Research, Henry Smith Charity, John Fell Fund, Oxford Biomedical Research Centre, NIHR, European Huntington\'s Disease Network e Enroll-HD.',
				),
				'en' => array(
					'Professor and Consultant in Neurogenetics, she is a Clinician Scientist at the University of Oxford and works at the Oxford University Hospitals NHS Trust, United Kingdom. She trained at the Royal Free Hospital School of Medicine, with an intercalated BSc in Biochemistry and Neuropharmacology (Royal Free and University College London), and completed her DPhil at the University of Oxford in 1995, in Professor Kay Davies\'s laboratory, as an MRC Clinical Training Fellow. She was then awarded an MRC Clinician Scientist Fellowship, held at the Wellcome Trust Centre for Human Genetics in Oxford, during which she developed her interest in ataxias and other movement disorders and went on to characterise several novel genetic ataxia syndromes. She completed her clinical training in Genetics as a Clinical Lecturer at the University of Oxford before becoming a Consultant in Neurogenetics and joining the Nuffield Department of Clinical Neurosciences (NDCN). In addition to the MRC, her research has been funded by Ataxia UK, Action Medical Research, the Henry Smith Charity, the John Fell Fund, the Oxford Biomedical Research Centre, NIHR, the European Huntington\'s Disease Network and Enroll-HD.',
				),
			),
		),
		'ibis-ariana-de-moraes'                      => array(
			'name' => 'Íbis Ariana de Moraes',
			'bio'  => array(
				'pt' => array(
					'Fisioterapeuta pela Universidade Presbiteriana Mackenzie (2010), mestre (2017) e doutora (2022) em Ciências pela Universidade de São Paulo, com estágio doutoral na University of Exeter (Reino Unido) e pós-doutorado em andamento na Faculdade de Medicina da USP. Especialista em Fisioterapia em Terapia Intensiva no Adulto (2022), Respiratória (2024) e Cardiovascular (2026) pelo COFFITO/ASSOBRAFIR. Professora e coordenadora do curso de Fisioterapia da Universidade Federal de Juiz de Fora, campus avançado de Governador Valadares (UFJF-GV), e orientadora do Programa de Pós-Graduação em Ciências da Reabilitação e Desempenho Físico Funcional da UFJF. Lidera o Núcleo de Investigação em Tecnologia, Reabilitação e Exercício da UFJF (NITRE-UFJF) e integra o Grupo de Pesquisa e Aplicação Tecnológica em Reabilitação da USP (PATER-USP).',
				),
				'en' => array(
					'Physiotherapist (Mackenzie Presbyterian University, 2010), MSc (2017) and PhD (2022) in Sciences from the University of São Paulo, with a doctoral research period at the University of Exeter (United Kingdom) and an ongoing postdoctoral fellowship at the USP School of Medicine. Specialist in Adult Intensive Care (2022), Respiratory (2024) and Cardiovascular (2026) Physiotherapy, certified by COFFITO/ASSOBRAFIR. Professor and coordinator of the Physiotherapy programme at the Federal University of Juiz de Fora, Governador Valadares campus (UFJF-GV), and supervisor in the UFJF Graduate Programme in Rehabilitation Sciences and Physical and Functional Performance. She leads the UFJF Research Centre on Technology, Rehabilitation and Exercise (NITRE-UFJF) and is a member of the Research and Technological Application in Rehabilitation Group at USP (PATER-USP).',
				),
			),
		),
	);

	return $speakers;
}

/**
 * The timetable: periods, each with its items; round tables nest talks.
 *
 * Item keys: time, end (optional), title, description (optional),
 * talks (optional, round tables). Talk keys: time, title, description
 * (optional), speakers (slugs), role ('speaker' | 'moderator').
 *
 * @return array<int,array<string,mixed>>
 */
function hint_get_program_schedule() {
	$discussion = array(
		'pt' => 'Discussão com a moderadora',
		'en' => 'Discussion with the moderator',
	);

	return array(
		array(
			'label' => array(
				'pt' => 'Manhã',
				'en' => 'Morning',
			),
			'items' => array(
				array(
					'time'  => '08:00',
					'title' => array(
						'pt' => 'Credenciamento',
						'en' => 'Check-in',
					),
				),
				array(
					'time'  => '08:30',
					'title' => array(
						'pt' => 'Abertura',
						'en' => 'Opening',
					),
				),
				array(
					'time'        => '09:00',
					'title'       => array(
						'pt' => 'Vivência I (Jogos CIATec)',
						'en' => 'Hands-on session I (CIATec games)',
					),
					'description' => array(
						'pt' => 'Uma experiência que integra ciência, tecnologia e práticas inclusivas, apresentando protocolos inovadores de avaliação e intervenção cognitiva e motora. Por meio da experimentação dos jogos digitais, os participantes poderão conhecer suas aplicações, explorar métricas de desempenho e compreender como tecnologias interativas podem contribuir para identificar barreiras funcionais, ampliar possibilidades de avaliação e apoiar estratégias de intervenção nas áreas da saúde e educação.',
						'en' => 'An experience that brings together science, technology and inclusive practice, presenting innovative protocols for cognitive and motor assessment and intervention. By trying out the digital games, participants will discover their applications, explore performance metrics and understand how interactive technologies can help identify functional barriers, broaden assessment possibilities and support intervention strategies in health and education.',
					),
				),
				array(
					/*
					 * Both sources head this table "10:00 às 11:30", yet both put the
					 * discussion at 11:20 (programa-v1: 11:20-12:00) and Vivência II at
					 * 12:00, so the table effectively runs until 12:00. Pending
					 * confirmation by the organisers.
					 */
					'time'  => '10:00',
					'end'   => '12:00',
					'title' => array(
						'pt' => 'Mesa redonda: Da ciência à vivência: avaliação, intervenção e inclusão na prática',
						'en' => 'Round table: From science to lived experience: assessment, intervention and inclusion in practice',
					),
					'talks' => array(
						array(
							'time'        => '10:00',
							'title'       => array(
								'pt' => 'Avaliações não restritivas de desempenho cognitivo e motor',
								'en' => 'Non-restrictive assessment of cognitive and motor performance',
							),
							'description' => array(
								'pt' => 'Abordagem multidisciplinar de métodos de avaliação inclusivos e não restritivos para investigar o desempenho cognitivo e motor, considerando a diversidade dos participantes, suas potencialidades individuais, as diferentes formas de interação e expressão e a eliminação de barreiras à participação.',
								'en' => 'A multidisciplinary approach to inclusive, non-restrictive assessment methods for investigating cognitive and motor performance, taking into account the diversity of participants, their individual potential, the different ways of interacting and expressing themselves, and the removal of barriers to participation.',
							),
							'speakers'    => array( 'carlos-bandeira-de-mello-monteiro' ),
							'role'        => 'speaker',
						),
						array(
							'time'        => '10:20',
							'title'       => array(
								'pt' => 'Protocolo de intervenção com jogos sérios inclusivos',
								'en' => 'Intervention protocol with inclusive serious games',
							),
							'description' => array(
								'pt' => 'Como estruturar protocolos de jogos sérios para apoiar atividades cognitivas e motoras, definindo objetivos, desafios e métricas de desempenho para favorecer a participação, a autonomia e a inclusão.',
								'en' => 'How to structure serious-game protocols that support cognitive and motor activities, defining goals, challenges and performance metrics that foster participation, autonomy and inclusion.',
							),
							'speakers'    => array( 'eduardo-dati-dias' ),
							'role'        => 'speaker',
						),
						array(
							'time'        => '10:40',
							'title'       => array(
								'pt' => 'A Voz de Quem Cuida: Vivências, Desafios e Perspectivas da Maternidade Atípica',
								'en' => 'The Voice of Those Who Care: Experiences, Challenges and Perspectives of Atypical Motherhood',
							),
							'description' => array(
								'pt' => 'Um diálogo entre a experiência de ser mãe atípica e o conhecimento científico, abordando os desafios das famílias, práticas baseadas em evidências e caminhos para promover autonomia, desenvolvimento e inclusão.',
								'en' => 'A dialogue between the experience of being an atypical mother and scientific knowledge, addressing the challenges families face, evidence-based practices and pathways to promote autonomy, development and inclusion.',
							),
							'speakers'    => array( 'ruth-caldeira-de-mello' ),
							'role'        => 'speaker',
						),
						array(
							'time'     => '11:00',
							'title'    => array(
								'pt' => 'Tecnologia Assistiva e Acessibilidade em Libras',
								'en' => 'Assistive Technology and Accessibility in Libras (Brazilian Sign Language)',
							),
							'speakers' => array( 'isabela-barbieri' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '11:20',
							'title'    => $discussion,
							'speakers' => array( 'talita-dias-da-silva-magalhaes' ),
							'role'     => 'moderator',
						),
					),
				),
				array(
					'time'  => '12:00',
					'title' => array(
						'pt' => 'Vivência II (utilização aplicada dos jogos)',
						'en' => 'Hands-on session II (applied use of the games)',
					),
				),
				array(
					'time'        => '12:30',
					'title'       => array(
						'pt' => 'Almoço',
						'en' => 'Lunch',
					),
					'description' => array(
						'pt' => 'Com pitch rápido dos trabalhos aceitos diante dos pôsteres.',
						'en' => 'With quick pitches of the accepted papers in front of the posters.',
					),
				),
			),
		),
		array(
			'label' => array(
				'pt' => 'Tarde',
				'en' => 'Afternoon',
			),
			'items' => array(
				array(
					'time'  => '13:30',
					'end'   => '15:00',
					'title' => array(
						'pt' => 'Mesa redonda: Desenho Universal para Aprendizagem (DUA) e inovação educacional: construindo caminhos para a participação e a aprendizagem de todos',
						'en' => 'Round table: Universal Design for Learning (UDL) and educational innovation: building pathways to participation and learning for all',
					),
					'talks' => array(
						array(
							'time'     => '13:30',
							'title'    => array(
								'pt' => 'DUA: conceitos, princípios e possibilidades na era das tecnologias digitais',
								'en' => 'UDL: concepts, principles and possibilities in the age of digital technologies',
							),
							'speakers' => array( 'eladio-sebastian-heredero' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '13:50',
							'title'    => array(
								'pt' => 'DUA e inovação educacional: tecnologias e estratégias para a aprendizagem de todos',
								'en' => 'UDL and educational innovation: technologies and strategies for learning for all',
							),
							'speakers' => array( 'eniceia-mendes' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '14:10',
							'title'    => array(
								'pt' => 'DUA na prática: construindo aprendizagens e fortalecendo uma rede inclusiva',
								'en' => 'UDL in practice: building learning and strengthening an inclusive network',
							),
							'speakers' => array( 'maraina-fernandes-de-aguiar' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '14:30',
							'title'    => $discussion,
							'speakers' => array( 'mariana-moraes-lopes' ),
							'role'     => 'moderator',
						),
					),
				),
				array(
					'time'  => '15:00',
					'title' => array(
						'pt' => 'Intervalo',
						'en' => 'Break',
					),
				),
				array(
					'time'  => '15:30',
					'end'   => '17:00',
					'title' => array(
						'pt' => 'Mesa redonda: IA aplicada a jogos sérios',
						'en' => 'Round table: AI applied to serious games',
					),
					'talks' => array(
						array(
							'time'     => '15:30',
							'title'    => array(
								'pt' => 'Semântica como infraestrutura de confiança: LLMs rastreáveis em saúde, da interoperabilidade de dados à inclusão neurodivergente',
								'en' => 'Semantics as an infrastructure of trust: traceable LLMs in healthcare, from data interoperability to neurodivergent inclusion',
							),
							'speakers' => array( 'jose-eduardo-santarem-segundo' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '15:50',
							'title'    => array(
								'pt' => 'Processamento de imagens para auxílio ao diagnóstico e terapias',
								'en' => 'Image processing to support diagnosis and therapies',
							),
							'speakers' => array( 'fatima-de-lourdes-dos-santos-nunes-marques' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '16:10',
							'title'    => array(
								'pt' => 'Uso de tecnologia para análise de padrões de movimento',
								'en' => 'Using technology to analyse movement patterns',
							),
							'speakers' => array( 'helen-dawes', 'talita-dias-da-silva-magalhaes' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '16:30',
							'title'    => array(
								'pt' => 'Do genoma ao smartphone: biomarcadores digitais em doenças neurogenéticas',
								'en' => 'From the genome to the smartphone: digital biomarkers in neurogenetic disorders',
							),
							'speakers' => array( 'andrea-nemeth', 'talita-dias-da-silva-magalhaes' ),
							'role'     => 'speaker',
						),
						array(
							'time'     => '16:50',
							'title'    => $discussion,
							'speakers' => array( 'ibis-ariana-de-moraes' ),
							'role'     => 'moderator',
						),
					),
				),
				array(
					'time'  => '17:30',
					'title' => array(
						'pt' => 'Encerramento',
						'en' => 'Closing',
					),
				),
			),
		),
	);
}

/**
 * <time> element for a clock time on the event day.
 *
 * @param string $time  "HH:MM".
 * @param string $class CSS class.
 */
function hint_render_program_time( $time, $class ) {
	?>
	<time class="<?php echo esc_attr( $class ); ?>" datetime="<?php echo esc_attr( HINT_PROGRAM_DATE . 'T' . $time . '-03:00' ); ?>"><?php echo esc_html( $time ); ?></time>
	<?php
}

/**
 * Speaker card: photo, name, role and the short bio in <details>.
 *
 * @param string $slug Key in hint_get_program_speakers().
 * @param string $role 'speaker' or 'moderator'.
 */
function hint_render_program_speaker( $slug, $role ) {
	$speakers = hint_get_program_speakers();

	if ( ! isset( $speakers[ $slug ] ) ) {
		return;
	}

	$speaker    = $speakers[ $slug ];
	$role_label = hint_pick(
		'moderator' === $role
			? array(
				'pt' => 'Moderadora',
				'en' => 'Moderator',
			)
			: array(
				'pt' => 'Palestrante',
				'en' => 'Speaker',
			)
	);
	$bio_label  = hint_pick(
		array(
			'pt' => 'Minicurrículo',
			'en' => 'Short bio',
		)
	);
	$bio        = hint_pick( $speaker['bio'] );
	$photo      = hint_asset_url( 'speakers/' . $slug . '.jpg' );
	?>
	<article class="hint-speaker hint-speaker--<?php echo esc_attr( $role ); ?>">
		<img
			class="hint-speaker__photo"
			src="<?php echo esc_url( $photo ); ?>"
			alt="<?php echo esc_attr( $speaker['name'] ); ?>"
			width="320"
			height="320"
			loading="lazy"
			decoding="async"
		>
		<div class="hint-speaker__body">
			<p class="hint-speaker__role"><?php echo esc_html( $role_label ); ?></p>
			<p class="hint-speaker__name"><?php echo esc_html( $speaker['name'] ); ?></p>
			<?php if ( is_array( $bio ) && ! empty( $bio ) ) : ?>
				<details class="hint-speaker__bio">
					<summary class="hint-speaker__bio-toggle"><?php echo esc_html( $bio_label ); ?></summary>
					<div class="hint-speaker__bio-text">
						<?php foreach ( $bio as $paragraph ) : ?>
							<p><?php echo esc_html( $paragraph ); ?></p>
						<?php endforeach; ?>
					</div>
				</details>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * One talk inside a round table (time, title, description, speaker cards).
 *
 * @param array<string,mixed> $talk Talk data.
 */
function hint_render_program_talk( $talk ) {
	$is_moderation = isset( $talk['role'] ) && 'moderator' === $talk['role'];
	?>
	<li class="hint-program__talk<?php echo $is_moderation ? ' hint-program__talk--moderation' : ''; ?>">
		<?php hint_render_program_time( $talk['time'], 'hint-program__time hint-program__time--talk' ); ?>
		<div class="hint-program__talk-body">
			<h5 class="hint-program__talk-title"><?php echo esc_html( hint_pick( $talk['title'] ) ); ?></h5>
			<?php if ( ! empty( $talk['description'] ) ) : ?>
				<p class="hint-program__description"><?php echo esc_html( hint_pick( $talk['description'] ) ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $talk['speakers'] ) ) : ?>
				<div class="hint-program__speakers">
					<?php
					foreach ( $talk['speakers'] as $slug ) {
						hint_render_program_speaker( $slug, isset( $talk['role'] ) ? $talk['role'] : 'speaker' );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</li>
	<?php
}

/**
 * One timetable item (activity or round table).
 *
 * @param array<string,mixed> $item Item data.
 */
function hint_render_program_item( $item ) {
	$is_table = ! empty( $item['talks'] );
	?>
	<li class="hint-program__item<?php echo $is_table ? ' hint-program__item--table' : ''; ?>">
		<p class="hint-program__slot">
			<?php hint_render_program_time( $item['time'], 'hint-program__time' ); ?>
			<?php if ( ! empty( $item['end'] ) ) : ?>
				<span class="hint-program__until">
					<span class="hint-program__time-sep" aria-hidden="true">–</span>
					<?php hint_render_program_time( $item['end'], 'hint-program__time hint-program__time--end' ); ?>
				</span>
			<?php endif; ?>
		</p>
		<div class="hint-program__body">
			<h4 class="hint-program__item-title"><?php echo esc_html( hint_pick( $item['title'] ) ); ?></h4>
			<?php if ( ! empty( $item['description'] ) ) : ?>
				<p class="hint-program__description"><?php echo esc_html( hint_pick( $item['description'] ) ); ?></p>
			<?php endif; ?>
			<?php if ( $is_table ) : ?>
				<ol class="hint-program__talks">
					<?php
					foreach ( $item['talks'] as $talk ) {
						hint_render_program_talk( $talk );
					}
					?>
				</ol>
			<?php endif; ?>
		</div>
	</li>
	<?php
}

/**
 * Output the Programme section.
 */
function hint_render_section_program() {
	$date_label = hint_pick(
		array(
			'pt' => 'Quinta-feira, 25 de fevereiro de 2027',
			'en' => 'Thursday, 25 February 2027',
		)
	);
	$venue      = hint_pick(
		array(
			'pt' => 'Auditório Vermelho, EACH-USP, São Paulo',
			'en' => 'Red Auditorium, EACH-USP, São Paulo',
		)
	);
	?>
	<section class="hint-section hint-section--program" id="program" aria-labelledby="program-title">
		<div class="hint-section__inner">
			<?php
			hint_render_section_heading(
				hint_pick(
					array(
						'pt' => 'Programação',
						'en' => 'Programme',
					)
				),
				hint_pick(
					array(
						'pt' => 'Programação científica',
						'en' => 'Scientific programme',
					)
				),
				'program-title'
			);
			?>
			<p class="hint-section__lead hint-program__when">
				<time datetime="<?php echo esc_attr( HINT_PROGRAM_DATE ); ?>"><?php echo esc_html( $date_label ); ?></time>
				<span class="hint-program__when-sep" aria-hidden="true">·</span>
				<span class="hint-program__where"><?php echo esc_html( $venue ); ?></span>
			</p>

			<?php foreach ( hint_get_program_schedule() as $index => $period ) : ?>
				<?php $period_id = 'program-period-' . ( $index + 1 ); ?>
				<div class="hint-program__period">
					<h3 class="hint-program__period-title" id="<?php echo esc_attr( $period_id ); ?>"><?php echo esc_html( hint_pick( $period['label'] ) ); ?></h3>
					<ol class="hint-program__list" aria-labelledby="<?php echo esc_attr( $period_id ); ?>">
						<?php
						foreach ( $period['items'] as $item ) {
							hint_render_program_item( $item );
						}
						?>
					</ol>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}
